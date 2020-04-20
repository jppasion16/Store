<?php
include_once($_SESSION["DocRoot"]."template/Header.php");
if(strlen($txtErrorMsg) == 0) $txtErrorMsg = "Unable to locate <code>".$_SERVER["REQUEST_URI"]."</code>";

?>

<h1>Invalid Page</h1>
<p><?=$txtErrorMsg; ?></p>
<a href="<?=$_SESSION["HomeDir"];?>">Click here to go back to home page</a>
<?php
include_once($_SESSION["DocRoot"]."template/Footer.php");
?>