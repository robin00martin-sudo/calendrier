<?php
/**
 * Connexion unique à la base de données (utilisée par index.php et calendrier.php).
 * Les paramètres sont lus dans config.local.php (non versionné) ou, à défaut,
 * dans les variables d'environnement DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS.
 */
function getDb(): PDO
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }

    $fichier = __DIR__ . '/config.local.php';
    $cfg = is_file($fichier) ? require $fichier : [
        'host'   => getenv('DB_HOST') ?: 'localhost',
        'port'   => getenv('DB_PORT') ?: 3306,
        'dbname' => getenv('DB_NAME') ?: 'formation',
        'user'   => getenv('DB_USER') ?: 'root',
        'pass'   => getenv('DB_PASS') ?: '',
    ];

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                   $cfg['host'], (int) $cfg['port'], $cfg['dbname']);
    $db = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $db;
}
