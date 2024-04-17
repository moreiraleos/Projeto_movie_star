<?php
$db_name = "movie_star";
$db_host = "localhost";
$db_user = "root";
$db_pass = "";

try {
    $conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
} catch (PDOException $e) {
    echo $e->getMessage();
}
// Habilitar erros PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
