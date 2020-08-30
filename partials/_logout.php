<?php
session_start();
echo"logging you out";

session_unset();
session_destroy();

header("location: ../")


?>