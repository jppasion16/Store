<?php

if(isset($_COOKIE["store-system-login-credentials"])){ // remove the last user of browser from database
    $intHandle = myOpenDB();
    list($txtLastUserName, $txtOldToken) = explode("%", $_COOKIE["store-system-login-credentials"]);
    $strQ = "DELETE UserLoginTokens.* FROM UserLoginTokens INNER JOIN Users ON UserLoginTokens.Userkey = Users.Autokey WHERE Users.UserName = '$txtLastUserName' AND UserLoginTokens.Token = '$txtOldToken'";
    myDeleteDB($strQ, $intHandle);
    setcookie("store-system-login-credentials", null, -3600, "/");
}

$txtHomeDir = $_SESSION["HomeDir"];

session_destroy();

header("Location: ".$txtHomeDir."login");

?>