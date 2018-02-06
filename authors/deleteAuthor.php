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
        <title>Delete Author - CMS</title>
    </head>
    <body>
        <?php
        if(isset($_POST['confirmDelete'])){
            
            require '../dbConnect.php';
            // Delete books bt author first
            try {
                $sql = "DELETE FROM bookstuff WHERE authorID = :authorID;";
                $s = $pdo->prepare($sql);
                $s->bindValue(':authorID', $id);
                $s->execute();
                
                $s = $pdo->prepare("DELETE FROM authors WHERE id = :authorID;");
                $s->bindValue(':authorID', $id);
                $s->execute();
            } catch (PDOException $ex) {
                $error = "Error performing delete of author $authName: " . $ex->getMessage();
                include '../error.html.php';
                exit();
            }
            
            //Check if deletions worked...
            
            if ($s->rowCount()){
                ?>
        <h3>Author <?= $authName ?> was success deleted</h3>
        <?php
            }else{
                  ?>
        <h3>Author <?= $authName ?> was NOT deleted</h3>
        <?php
            }
              ?>
        <a href="../authorsAdmin.php">Return to managing authors</a> | 
        <a href="../index.php">Display Updated Book List</a>
        <?php
            
        }else{
            

            
        ?>
        <br><br>
        <h3>Are you sure you want to delete <?= $authName ?> and all associated books?</h3>
        <br>
        <form action="" method="post">
            <input type="hidden" name="authID" value="<?= $id ?>">
            <input type="hidden" name="authName" value="<?= $authName ?>">
            <input type="submit" name="confirmDelete" value ="Yes">
            <a href="../authorsAdmin.php"><input type ="button" value="No"></a>
        </form>
        <?php
        }
        ?>
    </body>
</html>
