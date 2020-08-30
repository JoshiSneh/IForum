<?php 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("_dbconnect.php");
    if (!empty($_POST['signupemail']) && !empty($_POST['signupname']) && !empty($_POST['signuppassword'])&& !empty($_POST['signupcpassword']) ) {
        $useremail = $_POST["signupemail"];
        $username = $_POST['signupname'];
        $userpassword = $_POST["signuppassword"];
        $usercpassword = $_POST["signupcpassword"];
        //check whether this email exist
        $exsitsql = "SELECT * FROM users WHERE user_email= '$useremail'";
        $result = mysqli_query($conn,$exsitsql);
        $num = mysqli_num_rows($result);
        if($num == 1){
           header("location:../index.php?signuperror=1"); //email duplication error
        }else{
            if($userpassword === $usercpassword){
                $hash = password_hash($userpassword, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`user_name`,`user_email`, `user_pass`, `timestamp`) VALUES ( '$username', '$useremail', '$hash', current_timestamp());";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                header("location:../index.php?signupsuccess=true");
                }
            }else{
                header("location:../index.php?signuperror=2");//password matching error
            }
        }
    } else {
        header("location:../index.php?signuperror=3");
    }
}
