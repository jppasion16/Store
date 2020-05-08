<?php

$intHandle = myOpenDB();

$txtEmail = addslashes(trim($_POST["txtEmail"]));
$txtPassword = addslashes($_POST["txtPassword"]);
$txtTimeNow = date("Y-m-d H:i:s");

$strQ = "SELECT Autokey FROM Users WHERE Email = '$txtEmail'";
$rsResult = mySelectDB($strQ, $intHandle);
if(myNumRows($rsResult) == 0){
    $strQ = "INSERT INTO Users (UserName, Email, `Password`, LastEditBy, LastEditDate, CreatedBy, CreateDate) VALUES ('$txtEmail', '$txtEmail', PASSWORD('$txtPassword'), '$txtEmail', '$txtTimeNow', '$txtEmail', '$txtTimeNow')";
    if(myInsertDB($strQ, $intHandle)){
        $arrPost["IsCompleted"] = true;
    }
    else{
        $arrPost["txtErrorMsg"] = "Unexpected error. Please try again later.";
    }
}
else{
    $arrPost["txtErrorMsg"] = "<b>'$txtEmail'</b> has already been used.";
}
$arrPost["txtEmail"] = $txtEmail;

?>