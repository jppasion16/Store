<?php
include_once("assets/Functions.php");

if(isset($_GET["page"])){
    // echo $_GET["page"]; exit;
    PageExecute($_GET["page"]);
}
else PageExecute();
?>