<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Book Lister - using PDO</title>
        <link href="css/bookLister.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
        <?php
        require 'dbConnect.php'; 
        
        function callQuery($pdo, $query, $error){
            try{
                return $pdo->query($query);
            } catch (PDOException $ex) {
                $error .= $ex->getMessage();
                include 'error.html.php';
                exit;
            }
        }
        //Check to see if the user clicked the add a new book title link
        if (isset($_GET['clicked'])){
            $clicked = $_GET['clicked'];
        }else{
            $clicked = 0;
        }
        
        if ($clicked == 1){
            ?>
            <form action="<?= $_SERVER['PHP_SELF']?>" method="post">
                <label id="bookArea" for="newBookTitle">Enter the book's title</label><br>
                <textarea name="newBookTitle" id="newBookTitle" rows="10" cols="40">Enter book title</textarea>
                <br><br>
                <label id="bookAuthor" for="newAuthor">Enter the book's author</label><br>
                <input type="text" name="newAuthor" id="newAuthor">
                <br><br>
                <label id="genre" for="bookCategory">Enter book category</label><br>
                <select name="bookCategory" id="bookCategory">
                    <?php
                    
                    $closeSelect= true;
                    
                    $query = "SELECT * FROM categories;";
                    $error = "Error fetching book categories";
                    
                    $categoryResult = callQuery($pdo, $query, $error);
                    echo "\n";
                    //Now lets step through the resultset one row at a time
                    while($row = $categoryResult->fetch()){
                        ?>
                    <option value="<?= $row[0]?>"><?= $row[1]?></option>
                    <?php
                    }
                    ?>
                </select><br>
                <input type="submit" name="addBook" value="Add book">
                  
            </form>
            <?php
        }
        
        ?>
            <h2 id="topHeading">The Book Review</h2>
            
            <?php
            
        //check if user submitted the add a new book form if so validate.
        //
        if(isset($_POST['newBookTitle']) && $_POST['newBookTitle'] != "Enter book title" && $newBookTitle = trim(strip_tags($_POST['newBookTitle']))){
            //Now we have a "valid" book title
            //if an authon has been entered for the new book
            if(!$newAuthor = trim(strip_tags($_POST['newAuthor']))){
                $newAuthor = "Anonymous";
            }
            $newBookTitle = str_replace("'","\\'",$newBookTitle);
            $newAuthor = str_replace("'","\\'",$newAuthor);
            echo "<h3>New book title = $newBookTitle</h3>\n";
            echo "<h3>New author = $newAuthor</h3>";
            
            //Check if new book title already exists in our db
            $query = "SELECT COUNT(bookTitle) FROM bookstuff WHERE bookTitle = '$newBookTitle'";
            $error = "Error Fetching book title";
            $numBookTitles = callQuery($pdo, $query, $error)->fetchColumn();
            if ($numBookTitles) { //new book is a duplicate
                ?>
                <h3 style="color: #fff;">New book title <?= $newBookTitle ?> already exists - not added</h3>
            <?php
            } else { //new book is new book
              ?>
                <h3 style="color: #fff;">New book title <?= $newBookTitle ?> added</h3>
                <h3 style="color: #fff;">New book author <?= $newAuthor ?></h3>
            <?php  
            //now that we know we want to add a new book, we should also check if the new book's author already exists.
                $query = "SELECT COUNT(*) FROM authors WHERE authorName = '$newAuthor'";
                $error = "Error Fetching book author";
                $numAuthorRows = callQuery($pdo, $query, $error)->fetchColumn();
                if ($numAuthorRows){
                    ?>
                    <h3 style="color: #fff;">New author <?= $newAuthor ?> already exists - not added</h3>
                    <?php
                } else {
                    try{
                        //Use an sql prepared statement to prevent SQL injection attacks with this INSERT of $newAuthor
                        //:newAuthor below is a placeholder.
                        //PDO is smart enought to guard against dangerous characters
                        $sql = "INSERT INTO authors SET authorName = :newAuthor";
                        $s = $pdo ->prepare($sql); //$s is a pdo statement object
                        //bind our $newAuthor value into the ':newAuthor' placeholder
                        $s->bindValue(':newAuthor', $newAuthor);
                        
                        $s->execute();
                        ?>
                        <h3 style="color: #fff;">Your new author <?= $newAuthor ?> has been added.</h3>
                        <?php
                    } catch (PDOException $ex) {
                        $error = 'Error performing insert of author name: ' . $ex->getMessage();
                        include 'error.html.php';
                        exit;
                    }
                }//new author if
               
                //Now we are ready to insert our new book title, but wait, first we need to obtain the new books author's id.
                
                $query = "SELECT id FROM authors WHERE authorName = '$newAuthor';";
                $error = "Error fetching book author\'s id: ";
                $newAuthorResult = callQuery($pdo, $query, $error);
                
                //Extract the author's id from the result set
                $row = $newAuthorResult->fetch();
                
                $newAuthorId = $row['id'];
                if( isset($_POST['bookCategory'])){
                    $bookGenre = $_POST['bookCategory'];
                } else {
                    $bookGenre = -1;
                }
                
                try{
                        $sql = "INSERT INTO bookStuff(bookTitle, catId, authorID) VALUES(:newTitle, :bookGenre, $newAuthorId)";
                        $s = $pdo ->prepare($sql); 
                        
                        $s->bindValue(':newTitle', $newBookTitle);
                        $s->bindValue(':bookGenre', $bookGenre);
                    
                        $s->execute();
                        ?>
                        
                        <h3 style="color: #fff;">Your new book<?= $newBookTitle ?> has been added.</h3>
                        <?php
                    } catch (PDOException $ex) {
                        $error = 'Error performing insert of new book: ' . $ex->getMessage();
                        include 'error.html.php';
                        exit;
                    }
               
            } // new book if
            
        }  //Validation if  
//Run a query to retrieve our book categories
        $query = 'SELECT * FROM categories;';
        $error = "Error fetching book categories: ";
        $categoryResult = callQuery($pdo, $query, $error);
        // Step through the categories in our result set (PDOStatement object)
        //
        //While remaining rows in result set, fetch the next row
        
        while ($bookType = $categoryResult->fetch()) {
           $genreId = $bookType[0]; //get ID can use either method
           $genreName = $bookType['name']; //get name
         ?>
            <div class ="bookGenre">
                
                <h3><?= $genreName ?></h3>
        <?php 
            
            //Query for all books and authors(using $genreID and order
                $SQL = "SELECT b.bookTitle, a.authorName FROM bookstuff b, authors a WHERE a.id = b.authorID AND b.catId = $genreId ORDER BY 1 DESC;";
                $error = "Error fetching books: ";
                $bookResult = callQuery($pdo, $SQL, $error)
                ?>
                <blockquote>
            <?php
                //Step through the $booksResult result set and display each book in this categories 
            while ($book = $bookResult->fetch()){
            ?>
                    <p><?= $book['bookTitle']?><br><span class="author"><?= $book['authorName']?></span></p>
            <?php
            }
            ?>
                    
                </blockquote>
            </div>
    <?php
        }
        
        ?>
            <br><br>
            <a href="<?php echo "$_SERVER[PHP_SELF]?clicked=1"?>">Add new book title!</a>
        </div>
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"
                integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
                crossorigin="anonymous"></script>
                <script src="js/jquery.easing.1.3.js"></script>
                <script src="js/slidePanes.js"></script>
    </body>
</html>
