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
            min-height: 433px;
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
                    <a class="nav-link" href="">Home <span class="sr-only">(current)</span></a>
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
                    $sql1 = "SELECT category_name FROM `categories` LIMIT 5"  ;
                    $result = mysqli_query($conn, $sql1);
                    while($row1 = mysqli_fetch_array($result)){
                        $catname = $row1['category_name'];
                       echo '<a class="dropdown-item" href="threads.php?catname=' . $catname . '">'. $row1['category_name'].'</a>';
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
                <input class="form-control mr-sm-2" required name= "search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
                 <p class="text-light my-0 mx-2"> Welcome ' . $_SESSION['username'] . '</p>
                 <a href="partials/_logout.php" class="btn btn-outline-success mx-2">Logout</a>
            </form>';
            } else {
                echo '<form class="form-inline my-2 my-lg-0" action="search.php" method="GET">
                <input class="form-control mr-sm-2" required name="search" type="search" placeholder="Search" aria-label="Search">
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
    if (isset($_GET['signuperror']) && $_GET['signuperror'] == "1") {
        echo '<div class="alert alert-danger my-0 alert-dismissible h5 text-center fade show" role="alert">
                <strong>Error! Email already in Use </strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>';
    } elseif (isset($_GET['signuperror']) && $_GET['signuperror'] == "2") {
        echo '<div class="alert alert-warning my-0 h5 text-center alert-dismissible fade show" role="alert">
                    <strong>Error! Password doesnot match </strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';
    } elseif (isset($_GET['signuperror']) && $_GET['signuperror'] == "3") {
        echo '<div class="alert alert-warning my-0 h5 text-center alert-dismissible fade show" role="alert">
                    <strong>Error! Please fill all details </strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';
    }
    if (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] == "true") {
        echo '<div class="alert alert-success h5 text-center my-0 alert-dismissible fade show" role="alert">
                <strong>Success! You can Login now. </strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>';
    }

    if (isset($_GET['login']) && $_GET['login'] == "true" && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { // if login true
        echo '<div class="alert alert-success h5 text-center my-0 alert-dismissible fade show" role="alert">
                <strong>Welcome! You can contribute to our forum now. </strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>';
    } elseif (isset($_GET['loginerror']) && $_GET['loginerror'] == "1") { // if user press login without credentials
        echo '<div class="alert alert-danger h5 text-center my-0 alert-dismissible fade show" role="alert">
                <strong>Error! Fill all details before login. </strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>';
    } else if (isset($_GET['loginerror']) && $_GET['loginerror'] == "2") { // if password doesnot match
        echo '<div class="alert alert-danger h5 text-center my-0 alert-dismissible fade show" role="alert">
                <strong>Error! Wrong Password. </strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>';
    } elseif (isset($_GET['loginerror']) && $_GET['loginerror'] == "3") {
        echo '<div class="alert alert-danger my-0 h5 text-center alert-dismissible fade show" role="alert">
                <strong>Error! No registered Email found. </strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>';
    }
    ?>
    <div id="carouselExampleIndicators" class="carousel slide " data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/caro-1.jfif" class="d-block w-100" alt="...">
                <p>This is first</p>
            </div>
            <div class="carousel-item">
                <img src="img/caro-2.jfif" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="img/caro-3.jfif" class="d-block w-100" alt="...">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <div class="container my-4 ques">
        <h1 class="text-center">iForum- Discuss Categories</h1>
        <div class="row my-4">
            <!-- iterating through card for categories using loops -->
            <!-- fetching all categories -->
            <?php
            $sql = "SELECT * FROM `categories`";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $catid = $row['category_id'];
                $catname = $row['category_name'];
                $catdesc = $row['category_description'];

                echo '<div class="col-md-4 my-2">
                        <div class="card">
                            <img src="https://source.unsplash.com/500x400/?coding,' . $catname . '" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">' . $catname . '</h5>
                                <p class="card-text">' . $catdesc . '.</p>
                                <a href="threads.php?catname=' . $catname . '" class="btn btn-primary">View '.$catname.' Threads</a>
                            </div>
                        </div>
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