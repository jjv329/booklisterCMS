<?php

// 1) Connect to db server
// 2) Select our database
// 3) Chack for exceptions
try{
    
    //Create a new instance of a pdo object
    $pdo = new PDO('mysql:host=localhost:3306;dbname=webbooks;','bookListerUser','1');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
    
} catch (PDOException $ex) {
    $error = 'Unable to connect to the database server<br><br>' . $ex->getMessage();
    
    if($closeSelect){
        echo "</select>";
        $closeSelect = false;
    }
    
    include 'error.html.php';
    exit();
}