<?php

if (!session_id()){
    session_start();
}

//Check for user authentication

//If user is not authenticated redirect to login page

if (!isset($_SESSION['authenticated'])){
    //redirect to login.php
    header("Location: /booklisterCMS_jvollmer3/login.php?url=" . urlencode($_SERVER['SCRIPT_NAME']));
}