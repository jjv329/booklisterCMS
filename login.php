<?php

if (!session_id()){
    session_start();
}
//save any value in $_GET['url'] that was passed into this file in a session variable named targetURL
if(isset($_GET['url'])){
    $_SESSION['targetURL'] = $_GET['url'];
} else {
    $_SESSION['targetURL'] = "/bookListerCMS_jvollmer3/adminPage.php";
}

//If login form has been submitted, try to authenticate the user based on our DB user table
if(isset($_POST['clickIt'])){
    
    //process the form data
    //acquire the username and password from the form
    $uName = trim(strip_tags($_POST['userName']));
    $pWord = trim(strip_tags($_POST['passWord']));
    if ($pWord == "" || $uName == ""){
        echo "<h3 style =\"color:red\">Please enter both a username and password</h3>\n";
        
    }else { 
        require 'dbConnect.php'; 
        try{
            $sql= "SELECT pWord from users where uName = '$uName';";
            $password = $pdo->query($sql)->fetchColumn();
        } catch (PDOException $ex) {
            $error = 'Could not add user: ' . $ex->getMessage();
                    include 'error.html.php';
                    exit();
        }
        if(password_verify($pWord, $password)) {
            //authenticate the user since we have a match
            $_SESSION['authenticated'] = true;
            //Redirect to the page the user came here from or to the admin page
            header("Location: $_SESSION[targetURL]");
        }else{ //passwords do not match
            echo "<h3 style=\"color:red\">Invalid Credentials</h3>\n";
        }
    }
        
    
}else{
    //check to see if user wishes to log out
    if (isset($_GET['logOut']) && $_GET['logOut'] == 1){
        //log out the user
        unset($_SESSION['authenticated']);
        unset($authenticated);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BookLister CMS Admin Login Page</title>
    </head>
    <body>
        <h2 id="myh2">Please login to gain administrative access</h2>
        
        <form action="" method="post">
            <label for="userName">Username: </label>
            <input type="text" placeholder="Username" name="userName" id="userName">
            <br><br>
            <label for="passWord">Password: </label>
            <input type="password" placeholder="passWord" name="passWord" id="passWord">
            <br><br>
            <input type="submit" name="clickIt" value="Log in">
        </form>
        <?php
        
        ?>
    </body>
</html>
