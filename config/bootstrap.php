<?php

declare(strict_types=1);

use Dotenv\Dotenv;

/**
 * Chemins racine
 */
define('APP_ROOT', dirname(__DIR__));
define('PUBLIC_PATH', APP_ROOT . '/public');
define('CV_UPLOAD_DIR', PUBLIC_PATH . '/assets/pdf/cvs');
define('CV_PUBLIC_PREFIX', '/assets/pdf/cvs');

/**
 * 1) Autoload Composer (PSR-4 + dépendances)
 */
require_once APP_ROOT . '/vendor/autoload.php';

/**
 * 2) Charger .env (versionné)
 */
Dotenv::createMutable(APP_ROOT, '.env')->safeLoad();

/**
 * 3) Charger .env.local (non versionné) ET forcer la surcharge
 *    -> pas de parsing manuel : c'est Dotenv qui parse
 *    -> on applique juste l'override avec le tableau retourné
 */
$envLocalFile = APP_ROOT . '/.env.local';
if (is_file($envLocalFile)) {
    $loaded = Dotenv::createMutable(APP_ROOT, '.env.local')->safeLoad(); // array<string,string>

    foreach ($loaded as $key => $value) {
        $_ENV[$key] = $_SERVER[$key] = $value;
    }
}

/**
 * Helper env()
 */
if (!function_exists('env')) {
    function env(string $key, ?string $default = null): ?string
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

/**
 * Constantes globales
 */
define('APP_ENV',    env('APP_ENV', 'prod'));
define('APP_SECRET', env('APP_SECRET', 'change-me'));
define('APP_DEBUG',  (int) env('APP_DEBUG', '0') === 1);
define('APP_URL',    env('APP_URL', 'http://localhost'));

/**
 * DB
 */
define('DB_HOST', env('DB_HOST', '127.0.0.1'));
define('DB_PORT', (string) env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME', ''));
define('DB_USER', env('DB_USER', 'root'));

$pass = env('DB_PASSWORD', '');
if ($pass === 'null' || $pass === 'NULL') {
    $pass = null;
}
define('DB_PASS', $pass);

define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

define('DB_DSN', sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
    DB_HOST,
    DB_PORT,
    DB_NAME,
    DB_CHARSET
));

/**
* Constante sur la vue
*/
define('VIEW_PATH', APP_ROOT . '/Views'); // ou /templates

/**
 * Contexte applicatif
 */
mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Paris');

/**
 * Sécurité sessions
 */
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS']) ? '1' : '0'));
ini_set('session.use_strict_mode', '1');

// Démarrage sécurisé session
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Paramètres sécurité cookie (avant session_start)
    session_set_cookie_params([
        'lifetime' => 0, // session cookie
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS'])), // true en HTTPS
        'httponly' => true, // JS ne peut pas lire le cookie
        'samesite' => 'Lax', // protège contre CSRF basique
    ]);
    session_start();
}

/**
 * Protection contre la session fixation
 */
if (!isset($_SESSION['_initiated'])) {
    session_regenerate_id(true);
    $_SESSION['_initiated'] = true;
}