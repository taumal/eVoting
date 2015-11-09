<?php
require_once '.\assets\helper\adminAuh.php';
session_start();
session_destroy();
header("location:.\index.php");
?>