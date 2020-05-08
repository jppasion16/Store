<?php

if(isset($_SESSION["currUserName"])) header("Location: ".$_SESSION["HomeDir"]);

if(isset($_POST["IsCompleted"])) $txtStyle = "style=\"visibility:hidden\"";
else $txtStyle = "";

if(isset($_POST["txtErrorMsg"])) $txtErrorMsg = $_POST["txtErrorMsg"];

?>