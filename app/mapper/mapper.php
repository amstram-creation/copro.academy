<?php

declare(strict_types=1);

require_once 'add/badhat/db.php';

function map_list(string $table, array $conditions = [], array $options = []): array
{
    $options += ['limit' => null, 'offset' => 0, 'order' => 'created_at DESC'];

    [$where_sql, $where_binds] = qb_where($conditions);

    $sql = "SELECT * FROM $table $where_sql";
    if ($options['order']) $sql .= " ORDER BY {$options['order']}";
    if ($options['limit']) $sql .= " LIMIT {$options['limit']} OFFSET {$options['offset']}";

    return qp(db(), ($sql), $where_binds)->fetchAll();
}

// qb_where(['status' => 'published', 'user_id' => 5, 'tag_id' => [3, 4]])
// qb_where(['status' => 'published', 'user_id' => 5, 'tag_id' => [3, 4]], 'OR')
function qb_where(array $conds, string $connective = 'AND'): array
{
    if (!$conds) return ['', []];

    $where = [];
    $qbw_bindings = [];

    foreach ($conds as $col => $val) {
        if (null === $val)
            $clause = "$col IS NULL";
        else if (is_array($val))
            [$clause, $bind] = qb_in($col, $val, 'qbw_in');
        else
            [$clause, $bind] = qb_condition([$col => $val], '=', __FUNCTION__);

        $where[] = $clause;
        $qbw_bindings = array_merge($qbw_bindings, $bind ?? []);
    }

    return ['WHERE ' . implode(" $connective ", $where), $qbw_bindings];
}

// qb_in('tag_id', [3, 4])
// qb_in('status', ['published', 'draft'], 'allowed')
function qb_in(string $col, array $val, string $prefix = 'in'): array
{
    if (!$val) return ["1=0", []]; // or throw exception

    $bindings = $ph = [];
    foreach ($val as $i => $v) {
        $k = __qb_placeholder($prefix, $col, $i);
        $bindings[$k] = $v;
        $ph[] = $k;
    }
    return ["$col IN(" . implode(',', $ph) . ")", $bindings];
}

function qb_condition(array $data, string $default_op = '=', $andor = 'AND'): array
{
    $cubi = __qb_op($data, $default_op, __FUNCTION__);
    return [implode(" $andor ", $cubi[0]), $cubi[1]];
}


// __qb_op(['status' => 'published', 'user_id' => 5], '=')
// __qb_op(['status' => 'published', 'level<' => 5], '<>', 'sp')
function __qb_op(array $data, string $default_op = '=', string $prefix = 'qbc'): array
{
    $clauses = $bindings = [];
    $place_holder_count = -1;
    foreach ($data as $col => $val) {

        if (preg_match('/^(.+)\s*(=|!=|<>|<|>|<=|>=|LIKE|NOT LIKE|IS|IS NOT)$/i', $col, $m)) {
            $col = $m[1];
            $op = $m[2];
        } else {
            $op = $default_op;
        }

        $k = __qb_placeholder($prefix, $col, ++$place_holder_count);
        $bindings[$k] = $val;
        $clauses[] = $op ? "{$col} {$op} {$k}" : "{$col} {$k}";
    }
    return [$clauses, $bindings];
}

function __qb_placeholder(string $prefix, string $col, int $i): string
{
    return ":{$prefix}_{$col}_{$i}";
}
