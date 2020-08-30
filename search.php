<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>iForum</title>
    <style>
        .container {
            min-height: 85.1vh;
        }
    </style>
</head>

<body>
    <?php
    include("partials/_dbconnect.php");
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">iForum</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/forum">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Top Categories
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                        $sql1 = "SELECT category_name FROM `categories` LIMIT 5";
                        $result = mysqli_query($conn, $sql1);
                        while ($row1 = mysqli_fetch_array($result)) {
                            $catname = $row1['category_name'];
                            echo '<a class="dropdown-item" href="threads.php?catname=' . $catname . '">' . $row1['category_name'] . '</a>';
                        }
                        ?>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </li>
            </ul>
            <?php
            session_start();
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<form class="form-inline my-2 my-lg-0" action="search.php" method="GET">
                <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
                 <p class="text-light my-0 mx-2"> Welcome ' . $_SESSION['username'] . '</p>
                 <a href="partials/_logout.php" class="btn btn-outline-success mx-2">Logout</a>
            </form>';
            } else {
                echo '<form class="form-inline my-2 my-lg-0" action="search.php" method="GET">
                <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
                </form>
                <button class="btn btn-outline-success mx-2" data-toggle="modal" data-target="#loginModal">Login</button>
                <button class="btn btn-outline-success" data-toggle="modal" data-target="#signupModal">Signup</button>';
            }

            ?>
        </div>

    </nav>

    <?php include('partials/_loginmodal.php');
    include('partials/_signupmodal.php');
    ?>

    <!-- Search -->
    <div class="container my-3 ">
        <h1> Search Result for <em>"<?php echo $_GET['search']; ?></em>"</h1>
        <div class="result">
            <?php
            // $id = $_GET['threadid'];
            $noresults =true;
            $searchquery = $_GET['search'];
            $sql = "SELECT * FROM `threads` WHERE match (thread_title, thread_desc) against ('$searchquery')";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $noresults = false;
                $title = $row['thread_title'];
                $desc = $row['thread_desc'];

                $title = str_replace(">", "&gt;", "$title");
                $desc = str_replace("<", "&lt;", "$desc");

                $title = str_replace("<", "&lt;", "$title");
                $desc = str_replace(">", "&gt;", "$desc");
                
                $thread_id = $row['thread_id'];
                $url = "threadques.php?threadid=".$thread_id;
                //display search result
                
                echo '<h3><a href="'.$url.'">'.$title.'</a></h3>
                       <p>'.$desc.'</p>';
                    }

            if($noresults){
                echo '<div class="jumbotron my-4">
                            <h1 class=" display-4">No Result Found</h1>
                            <p class="lead"><?php echo $desc ?></p>
                            Suggestions: <br>
                            &bullet; Make sure that all words are spelled correctly. <br>
                            &bullet;Try different keywords. <br>
                            &bullet;Try more general keywords. <br>
                            </p>
                    </div>';
            }
            ?>
            
        </div>
    </div>
    <div class="container-fluid bg-dark text-white p-2">
        <p class="text-center my-2 mb-0"> Copyrigth&copy; iForum 2021. All right reserved</p>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">


    </script>
</body>

</html>