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
        .ques {
            min-height: 180px;
        }
    </style>
</head>

<body>
    <?php
    include("partials/_dbconnect.php") ?>
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
                <input class="form-control mr-sm-2" type="search" name="search"  placeholder="Search" aria-label="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button> <p class="text-light my-0 mx-2"> Welcome ' . $_SESSION['username'] . ' <a href="partials/_logout.php" class="btn btn-outline-success mx-2">Logout</a>
            </form>';
            } else {
                echo '<form class="form-inline my-2 my-lg-0" action="search.php" method="GET">
                <input class="form-control mr-sm-2" type="search" name="search"  placeholder="Search" aria-label="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
                </form>
                <button class="btn btn-outline-success mx-2" data-toggle="modal" data-target="#loginModal">Login</button>
                <button class="btn btn-outline-success" data-toggle="modal" data-target="#signupModal">Signup</button>';
            }

            ?>
        </div>
    </nav>
    <?php include('partials/_loginmodal.php');
    include('partials/_signupmodal.php'); ?>
    <div class="container my-4 ques">
        <?php
        $id = $_GET['threadid'];
        $sql = "SELECT * FROM `threads` WHERE thread_id='$id'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];

            $title = str_replace(">", "&gt;", "$title");
            $desc = str_replace("<", "&lt;", "$desc");

            $title = str_replace("<", "&lt;", "$title");
            $desc = str_replace(">", "&gt;", "$desc");

            $threadid = $row['thread_user_id'];

            // query for to diplay name in jumbotron
            $sql3 = "SELECT user_name FROM `users` WHERE sno = '$threadid'";
            $result3 = mysqli_query($conn, $sql3);
            $row3 = mysqli_fetch_assoc($result3);
            $postedby = $row3['user_name'];
        }
        ?>

        <div class="jumbotron">
            <h1 class=" display-4"><?php echo $title ?></h1>
            <p class="lead"><?php echo $desc ?></p>
            <hr class="my-4">
            <h2>Rules of Forum</h2>
            <p> &bullet; No Spam / Advertising / Self-promote in the forums. <br>
                &bullet; Do not post copyright-infringing material. <br>
                &bullet; Do not post “offensive” posts, links or images.<br>
                &bullet; Do not cross post questions.<br>
                &bullet; Remain respectful of other members at all times.</p>
            <p>Posted By: <b><?php echo $postedby ?></b></p>
        </div>
    </div>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<div class="container my-4">
        <h2>Post a Comment</h2>
        <form action="' . $_SERVER['REQUEST_URI'] . '" method="POST">
            <div class="form-group ">
                <label for="desc">Type Your Comment</label>
                <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
                <input type="hidden" name="sno" value="' . $_SESSION['usersno'] . '">
            </div>
            <button type="submit" class="btn btn-success">Post Comment</button>
        </form>
    </div>';
    } else {
        echo '<div class="container">
        <div class="jumbotron">
        <h1 class=" display-4">Post a Comment</h1>
            <p class="lead">You are not logged in please login to continue</p>
        </div></div?';
    }
    ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['comment'])) {
            $comment = $_POST["comment"];
            $comment = str_replace("<", "&lt;", "$comment");
            $comment = str_replace(">", "&gt;", "$comment");
            $usersno = $_POST['sno'];
            $sql = "INSERT INTO `comments` ( `comment_content`, `thread_id`, `comment_by`, `comment_dt`) VALUES ('$comment', '$id', '$usersno', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Successs!</strong> comment has been added successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div></div>';
            }
        } else {
            echo '<div class="container"><div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Post your comment first.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div></div>';
        }
    }
    ?>
    <div class="container ques">
        <h1 class="text-center py-2">Discussions</h1>
        <?php
        $id = $_GET['threadid'];
        $sql = "SELECT * FROM `comments` WHERE thread_id='$id'";
        $result = mysqli_query($conn, $sql);
        $noquestions = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noquestions = false;
            $id = $row['comment_id'];
            $content = $row['comment_content'];
            $time = $row['comment_dt'];
            $comment_by = $row['comment_by'];
            $sql2 = "SELECT * FROM `users` WHERE sno = '$comment_by'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            echo '<div class="media my-4">
                    <img src="img/user.png" class="mr-3" alt="user" width="47px" height="47px">
                    <div class="media-body">
                    <p class="font-weight-bold my-0">' . $row2['user_name'] . ' at ' . $time . '</p>
                        ' . $content . '
                    </div>
                </div>';
        }
        if ($noquestions) {
            echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <p class="display-4">No Comments</p>
                        <p class="lead">Be the first one to Comment.</p>
                    </div>
            </div>';
        }
        ?>
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