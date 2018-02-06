<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create CMS Admin Accounts</title>
    </head>
    <body>
        <?php
           //Connect to our DB server and select webbooks DB
            require 'dbConnect.php';
           //Drop any existing users table
           try {
            $pdo->exec("DROP TABLE IF EXISTS users;");
           } catch (PDOException $ex) {
             $error = 'Could not drop table: ' . $ex->getMessage();
             include 'error.html.php';
           }
           
           //Create table
           try{
               $sql = "CREATE TABLE users
                       (uName VARCHAR(255) primary key,
                       pWord VARCHAR(255))";
               $pdo->exec($sql);
           } catch (PDOException $ex) {
               $error = 'Could not create table: ' . $ex->getMessage();
               include 'error.html.php';
           }
           //Get user info from txt file and insert generated account info users table
           $fp = fopen("ids_fa2017.txt", "r");
           while (!feof($fp)) {
               $userName = strtolower(trim(fgets($fp,255)));
               
               if ($userName != ""){
                   
                   //Break up line into fields
                   list($fName,$lName) = explode(" ",$userName);

                   //Construct the username and password
                   //first character of the firstname followed by the last name
                   $userName = substr($fName, 0,1) . $lName;
                   
                   //first 4 chars of last name if present, then length of last name then first name uppercase first letter.
                   
                   $pWord = substr($lName,0,4) . strlen($lName) . ucfirst($fName);
                   
                   $md5password = md5($pWord);
                   $sha1password = sha1($pWord);
                   $hashedPassword = password_hash($pWord, PASSWORD_DEFAULT);
                   $anotherHashedPassword = password_hash($pWord, PASSWORD_DEFAULT);
                   
                   echo "<h3 style=\"color:green\">Username: $userName<br>Password: $pWord<br>md5: $md5password<br>SHA1: $sha1password<br>New Hash: $hashedPassword<br>2nd Hash: $anotherHashedPassword</h3>";
                   try {
                    $sql = "INSERT INTO users VALUES(:uName,:pWord);";
                    
                    $s = $pdo->prepare($sql);
                    
                    $s->bindvalue(':uName', $userName);
                    $s->bindvalue(':pWord', $hashedPassword);
                    
                    $s->execute();
                    echo "<h3 style=\"color:purple\"> Your new user $userName has been created</h3>";
                    
                    
                   } catch (PDOException $ex) {
                   $error = 'Could not add user: ' . $ex->getMessage();
                    include 'error.html.php';
                    exit();
                   }
               }
           }
        ?>
    </body>
</html>
