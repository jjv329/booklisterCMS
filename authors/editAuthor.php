<?php

if (isset($_GET['authID'])){
    $id = $_GET['authID'];
   
} else {
    header("Location: http://localhost:9090/booklisterCMS_jvollmer3/authorsAdmin.php");
}

if (isset($_GET['authName'])){
    $authName = $_GET['authName'];
}
require '../authenticate.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Author - CMS</title>
    </head>
    <body>
        <?php
        if(isset($_POST['edit']) && $_POST['authName'] != "" && $_POST['authName'] != $_POST['authNameOrig']){
            
            require '../dbConnect.php';
            // Delete books bt author first
            try {
                $sql = "UPDATE authors SET authorName = :authorName WHERE id = :authorID;";
                $s = $pdo->prepare($sql);
                $s->bindValue(':authorID', $id);
                $s->bindValue(':authorName', $_POST['authName']);
                $s->execute();
                
            } catch (PDOException $ex) {
                $error = "Error performing editing of author $authName: " . $ex->getMessage();
                include '../error.html.php';
                exit();
            }
            
            //Check if deletions worked...
            
            if ($s->rowCount()){
                ?>
        <h3>Author <?= $authName ?> was success edited</h3>
        <?php
            }else{
                  ?>
        <h3>Author <?= $authName ?> was NOT edited</h3>
        <?php
            }
              ?>
        <a href="../authorsAdmin.php">Return to managing authors</a> | 
        <a href="../index.php">Display Updated Book List</a>
        <?php
            
        }else{
            

            
        ?>
        <br><br>
        <h3>Edit <?= $authName ?></h3>
        <br>
        <form action="" method="post">
            <input type="hidden" name="authID" value="<?= $id ?>">
            <input type="hidden" name="authNameOrig" value="<?= $authName ?>">
            <input type="text" name="authName" value="<?= $authName ?>">
            <input type="submit" name="edit" value ="Edit">
        </form>
        <?php
        }
        ?>
    </body>
</html>
