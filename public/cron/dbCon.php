<?php

require_once '../../vendor/autoload.php';
$app = require_once '../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

\Dotenv::load($app->environmentPath(), $app->environmentFile());

$DB_HOST=env('DB_HOST');
$DB_DATABASE=env('DB_DATABASE');
$DB_USERNAME=env('DB_USERNAME');
$DB_PASSWORD=env('DB_PASSWORD');
$DB_PORT=env('DB_PORT');
$mysqli = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE, $DB_PORT);

/*
 * Set character Set to utf8
 * Otherwise special character will break.
 * Default character set is 'latin1'
 */
$mysqli->set_charset("utf8");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>