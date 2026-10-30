<?php
// Default XAMPP credentials. Change here if your MySQL root has a password.
$pdo = new PDO('mysql:host=127.0.0.1;dbname=pizza_ordering_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
