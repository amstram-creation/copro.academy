<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../..');

require 'add/badhat/build.php';
require 'add/badhat/error.php';
require 'add/badhat/core.php';
require 'add/badhat/db.php';
require 'add/badhat/auth.php';

require 'app/morph/html.php';


// vd(qp(db(),"INSERT INTO operator (label, username, password_hash, status) VALUES (?, ?, ?, 1)",['jp', 'jp', password_hash('jp', PASSWORD_DEFAULT)]));die;
try {
    auth(AUTH_SETUP, 'operator.username', "SELECT `password_hash` FROM `operator` WHERE `username` = ?");
    l(null, require 'app/lang/fr.php'); // Load French translations

    $re_quest   = http_in(4096, 9);
    $io = realpath(__DIR__ . '/../io');

    $request_admin = strpos($re_quest, '/admin') === 0;
    $request_admin && auth(AUTH_GUARD);

    // business: find the route and invoke it
    $in_path    = $io . '/route';
    $in_route   = io_route($in_path, $re_quest, IO_DEEP | IO_FLEX) ?: io_route($in_path, 'index');
    $in_quest   = io_fetch($in_route, [], IO_INVOKE);

    // render: match route file and absorb it when possible
    $out_path   = $io . '/render';
    $out_route = [IO_PATH => (str_replace($in_path, $out_path, $in_route[IO_PATH]) ?? '')] + $in_route;
    $out_quest  = io_fetch($out_route, $in_quest[IO_INVOKE], IO_ABSORB);

    // absorption is optional, http_body() settles the output
    if (is_string($out_quest[IO_OB_GET]) || is_string($out_quest[IO_ABSORB])) {
        http_out(200, http_body($out_quest, $request_admin), ['Content-Type' => 'text/html; charset=utf-8']);
        exit;
    }

    error_log('404 Not Found for ' . $re_quest);
    http_out(404, 'Not Found at all');

} catch (LogicException | RuntimeException $t) {
    handle_badhat_exception($t);
    header('HTTP/1.1 500 Forbidden');
} catch (Throwable $t) {
    vd(0, $t);
    // out quest that fetch an error page within the layout, firsttests if error page has 200 
    die;
}

function http_body(array $out_quest, bool $request_admin): string
{
    if (isset($out_quest[IO_ABSORB]))
        return $out_quest[IO_ABSORB];

    $template = $request_admin ? 'app/io/render/admin/layout.php' : 'app/io/render/layout.php';
    return ob_ret_get($template, ['main' => $out_quest[IO_OB_GET]])[1];
}

function handle_badhat_exception(Throwable $t): void
{
    if ((int)$t->getCode() === 403) {
        $u = parse_url($_SERVER['HTTP_REFERER'] ?? '');
        parse_str($u['query'] ?? '', $q);

        if (isset($q['error'])) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $q['error'] = $t->getMessage();
        header('Location: ' . ($u['path'] ?: '/') . '?' . http_build_query($q) . (isset($u['fragment']) ? "#{$u['fragment']}" : ''));
        exit;
    }

    if ((int)$t->getCode() === 401) {
        header('Location: /login');
        exit;
    }
}
