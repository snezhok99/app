<?php

$host = 'd';
$db = 'l';
$user = 'root';
$pass = 's';

try {
    $pdo = new PDO("mysql:host=$host; dbname=$db", $user, $pass);

} catch (PDOException $e) {
    echo 'Ошибка соединения с базой данных' .$e->getMessage();       
}





