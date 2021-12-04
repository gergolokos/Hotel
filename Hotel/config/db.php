<?php

const DB_HOST = 'localhost';
const DB_NAME = 'hotel_project';
const DB_USER = 'root';
const DB_PASSWORD = '';

try {
    $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
    echo "Database error! <br>";
    echo $e->getMessage();
    die();
}

require 'functions.php';
