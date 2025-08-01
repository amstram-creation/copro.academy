<?php

const AUTH_SETUP = 1;
const AUTH_VERIFY = 2;
const AUTH_REVOKE = 4;
const AUTH_ERROR = 16;
const AUTH_GUARD = 32;

const CSRF_KEY = '_csrf_token';

session_status() === PHP_SESSION_NONE  && session_start() || throw new RuntimeException('Session cannot be started', 500);

// 1. auth(AUTH_SETUP, 'username', 'SELECT password FROM .. WHERE ..'); init the field names
// 3. auth(AUTH_VERIFY, 'myusername', 'superhashedunreadablepassword'); logins
// 4. auth(AUTH_REVOKE); logout
// 5. auth(AUTH_GUARD); check if logged in, throw exception if not
// 2. auth(); check if logged in return username or null
function auth(int $behave = 0, ?string $u = null, ?string $p = null): ?string
{
    static $username_field = null;
    static $password_query = null;

    $behave & AUTH_SETUP    && $u && $p && ($username_field = $u) && ($password_query = $p);

    $behave & AUTH_REVOKE   && session_destroy() && ($password_query = $username_field = null);

    $behave & AUTH_GUARD    && !isset($username_field, $_SESSION[$username_field])       && throw new RuntimeException('Unauthorized', 401);

    $behave & AUTH_VERIFY   && !isset($u, $p, $username_field, $password_query)          && throw new BadFunctionCallException('Username and password must be setup and provided');
    $behave & AUTH_VERIFY   && isset($_SESSION[$username_field])                         && throw new BadFunctionCallException('Already logged in');
    $behave & AUTH_VERIFY   && (empty($_POST) || empty($_POST[$u]) || empty($_POST[$p])) && throw new BadMethodCallException('Login must be a valid POST request');

    $behave & AUTH_VERIFY   && ($db_password = qp(db(), $password_query, [$_POST[$u]])) && ($db_password = ($db_password->fetchColumn()))
        && password_verify($_POST[$p], ($db_password)) && session_regenerate_id(true)
        && ($_SESSION[$username_field] = $_POST[$u]);
    return $_SESSION[$username_field] ?? null;
}

function csp_nonce(): string
{
    static $nonce = null;
    return $nonce ??= bin2hex(random_bytes(16));
}

function csrf_token(int $ttl = 3600): string
{
    $data = $_SESSION[CSRF_KEY] ?? ['value' => '', 'expires_at' => 0];
    $now  = time();
    if (empty($data['value']) || $now > $data['expires_at']) {
        $data['value']      = bin2hex(random_bytes(32));
        $data['expires_at'] = $now + $ttl;
        $_SESSION[CSRF_KEY] = $data;
    }

    return $data['value'];
}

function csrf_field(int $ttl = 3600): string
{
    return '<input type="hidden" name="' . CSRF_KEY . '" value="' . htmlspecialchars(csrf_token($ttl), ENT_QUOTES) . '" />';
}

function csrf_validate(?string $token = null): bool
{
    $token = $token ?? ($_POST[CSRF_KEY] ?? '');
    if ($token === '')
        return false;

    $data = $_SESSION[CSRF_KEY] ?? ['value' => '', 'expires_at' => 0];
    $now  = time();
    return !empty($data['value'])
        && hash_equals($data['value'], $token)
        && $now <= $data['expires_at'];
}


// HTTP-HMAC AUTH
function auth_http(): ?string
{
    $user = $_SERVER['HTTP_X_AUTH_USER'] ?? '';
    $sig  = $_SERVER['HTTP_X_AUTH_SIG']  ?? '';
    if ($user === '' || $sig === '') {
        return null;
    }

    $secret = getenv('BADHAT_AUTH_HMAC_SECRET')
        ?: throw new DomainException('HMAC secret missing', 500);

    $expected = hash_hmac('sha256', $user, $secret);
    return hash_equals($expected, $sig) ? $user : null;
}
