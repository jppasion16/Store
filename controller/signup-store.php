<?php

function myRenderFromQuery($strQ){
    $intHandle = myOpenDB();
    myInsertDB("INSERT INTO tmpErrors (`Description`) VALUES ('".addslashes($strQ)."')", $intHandle);
    $rsResult = mySelectDB($strQ, $intHandle);
    $arrCode = array();
    $arrDesc = array();
    while($objRow = myFetchObj($rsResult)){
        array_push($arrCode, $objRow->Code);
        array_push($arrDesc, $objRow->Description);
    }
    return myOptionRendering($arrCode, $arrDesc);
}


if(isset($_POST["IsAjax"])){
    include_once("../assets/Functions.php");
    switch(strtoupper($_POST["txtFind"])){
        case "PROVINCE" :
            $tblName = "Province";
            $colSource = "RegCode";
        break;
        case "CITYMUN" :
            $tblName = "CityMun";
            $colSource = "ProvCode";
        break;
        case "BRGY" :
            $tblName = "Barangay";
            $colSource = "CityMunCode";
        break;
    }
    $strQ = "SELECT Code, `Description` FROM $tblName WHERE $colSource = '".addslashes($_POST["txtCode"])."'";
    echo myRenderFromQuery($strQ);
}

else{
    $strQ = "SELECT Code, `Description` FROM Region";
    $opnRegion = myRenderFromQuery($strQ);
}
?>