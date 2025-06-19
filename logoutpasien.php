<?php
session_start();
session_destroy();
header("Location: loginpasien.php");
exit();
