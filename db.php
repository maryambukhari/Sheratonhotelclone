<?php
$host = 'localhost'; // Assuming localhost, change if needed
$dbname = 'dbnpu9zg6u2od9';
$user = 'uxhc7qjwxxfub';
$pass = 'g4t0vezqttq6';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
