<?php
if(!session_id()){
    session_start();
}
require 'authenticate.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CMS - Authors Admin Page</title>
        <link href="css/authorsAdmin.css" rel="stylesheet">
    </head>
    <body>
        <h2>Manage Authors</h2>
        <?php
        require 'dbConnect.php';
        
        //Query our DB and get all author information
        try{
            $result = $pdo->query("SELECT * FROM authors ORDER BY authorName;");
        } catch (PDOException $ex) {
            $error = "Error fetching author info: " . $ex->getMessage();
            include 'error.php';
            exit();
        }
        
        ?>
        <table>
            <?php
            //step through the result set (PDOStatement object = $result) one row at a time.
            while($row = $result->fetch()){
                
                print <<<TABLESTUFF
                <tr>
                    <td class="author">$row[1]</td>
                    <td class="authorid">$row[0]</td>
                 
                   <td class = "links">
                        <a href ="authors/editAuthor.php?authID=$row[0]&authName=$row[1]"">Edit</a><br>
                        <a href ="authors/deleteAuthor.php?authID=$row[0]&authName=$row[1]">Delete</a>
                   </td>
                </tr>
                   
           
                
TABLESTUFF;
                
            }
            ?>
        </table>        
    </body>
</html>
