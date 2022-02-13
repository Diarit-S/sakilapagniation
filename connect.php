<?php
try{
    // Connexion à la base
    $db = new PDO('mysql:host=163.172.130.142:3310;dbname=sakila', 'etudiant', 'CrERP29qwMNvcbnAMgLzW9CwuTC5eJHn');
    $db->exec('SET NAMES "UTF8"');
} catch (PDOException $e){
    echo 'Error : '. $e->getMessage();
    die();
}
