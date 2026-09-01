<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'vehicle_service_centre');
define('DB_CHARSET', 'utf8mb4');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset(DB_CHARSET);
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Sorry, we are experiencing technical difficulties. Please try again later.");
}

function close_db_connection($conn) {
    if ($conn instanceof mysqli) {
        $conn->close();
    }
}
?>
