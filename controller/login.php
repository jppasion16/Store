<?php

function KeepMeLogin($intHandle, $intUserkey, $txtUserName){
    $intUserkey = 0 + (int)$intUserkey;
    $txtIPAdd = $_SERVER['REMOTE_ADDR'];
    if($intUserkey){
        if(isset($_COOKIE["store-system-login-credentials"])){ // remove the last user of browser from database
            list($txtLastUserName, $txtOldToken) = explode("%", $_COOKIE["store-system-login-credentials"]);
            $strQ = "DELETE UserLoginTokens.* FROM UserLoginTokens INNER JOIN Users ON UserLoginTokens.Userkey = Users.Autokey WHERE Users.UserName = '$txtLastUserName' AND UserLoginTokens.Token = '$txtOldToken'";
            myDeleteDB($strQ, $intHandle);
        }
        
        $txtNewToken = hash("sha512", rand().$txtUserName.$txtIPAdd.date("Y-m-d H:i:s"));

        $strQ = "INSERT INTO UserLoginTokens (Userkey, Token, CreatedBy, LastEditBy) VALUES ($intUserkey, '$txtNewToken', '$txtUserName', '$txtUserName')";
        myInsertDB($strQ, $intHandle);

        // Try to update the time to never expires
        // as of now I use 2147483647
        // 2147483647 - maximum value of 32-bit minus 1 (2038-01-19 04:14:07)
        setcookie("store-system-login-credentials", "$txtUserName%$txtNewToken", 2147483647, "/");
    }
}

if(isset($_SESSION["currUserName"])){
    header("Location: ".$_SESSION["HomeDir"]);
    exit;
}

$boolLoginPage = true;

$txtUserName = "";
$txtPassword = "";
$chkKeepLogin = "";

if(isset($_POST["IsAjax"]) OR isset($_COOKIE["store-system-login-credentials"])){
    if(isset($_POST["IsAjax"])){
        $method = "ajax";
        // include functions because it is used as ajax url
        include_once("../assets/Functions.php");
        $intHandle = myOpenDB();
        $txtUserName = trim(addslashes($_POST["txtUserName"]));
        $txtPassword = trim(addslashes($_POST["txtPassword"]));
        $strQ = "SELECT U.Autokey AS Userkey, S.Autokey AS Storekey, S.Description AS StoreDesc, S.StoreNo FROM Users U INNER JOIN Stores S ON U.Storekey = S.Autokey WHERE U.UserName = '$txtUserName' AND U.Password = PASSWORD('$txtPassword') LIMIT 1";
    }
    else{
        $method = "cookie";
        $chkKeepLogin = "checked";
        $intHandle = myOpenDB();
        list($txtUserName, $txtLoginToken) = explode("%", addslashes($_COOKIE["store-system-login-credentials"]));
        $strQ = "SELECT U.Autokey AS Userkey, S.Autokey AS Storekey, S.Description AS StoreDesc, S.StoreNo FROM Users U INNER JOIN Stores S ON U.Storekey = S.Autokey INNER JOIN UserLoginTokens T ON U.Autokey = T.Userkey WHERE U.UserName = '$txtUserName' AND T.Token = '$txtLoginToken' LIMIT 1";
    }
    $rsResult = mySelectDB($strQ, $intHandle);

    if(myNumRows($rsResult) > 0){
        $_SESSION["currUserName"] = $txtUserName;
        $objRow = myFetchObj($rsResult);
        $_SESSION["storeKey"] = $objRow->Storekey;
        $_SESSION["storeName"] = $objRow->StoreDesc;
        $_SESSION["storeNo"] = $objRow->StoreNo;
        
        switch(strtolower($method)){
            case "ajax":
                // check if keep me logged in was checked
                if($_POST["chkKeepLogin"]){
                    $chkKeepLogin = "checked";
                    KeepMeLogin($intHandle, $objRow->Userkey, $txtUserName);
                }
                echo "1";
            break;
            case "cookie":
                header("Location: ".$_SESSION["HomeDir"]);
            break;
        }
    }
    else{
        if($boolIsPosted) echo "0";
    }
}

?>