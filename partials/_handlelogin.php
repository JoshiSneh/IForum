<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("_dbconnect.php");
    if (!empty($_POST['loginemail']) && !empty($_POST['loginpass'])) {
        $useremail = $_POST["loginemail"];
        $userpassword = $_POST["loginpass"];
        $sql = "SELECT * FROM users WHERE user_email= '$useremail'";
        $result = mysqli_query($conn,$sql);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            $row = mysqli_fetch_assoc($result);
            if(password_verify($userpassword,$row['user_pass'])){
                $sql1 = "SELECT user_name FROM `users` WHERE user_email ='$useremail'";
                $result = mysqli_query($conn, $sql1);
                $row1 = mysqli_fetch_assoc($result);
                $username = $row1['user_name'];
                $usersno = $row['sno'];
                 session_start();
                 $_SESSION['loggedin'] = true;
                 $_SESSION['useremail'] = $useremail;
                 $_SESSION['username'] = $username;
                 $_SESSION['usersno'] = $usersno;
                header("location:../index.php?login=true");
            }else{
                header("location:../index.php?loginerror=2");
            }
        }else{
            header("location:../index.php?loginerror=3");
        } 
        }else{
            header("location:../index.php?loginerror=1");
        }
    } 


?> 