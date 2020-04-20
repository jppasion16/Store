<?php
/*
    Created by: John Paul Pasion 20200330
*/
function myRenderStoreHTML($rsResult){
    $txtRet = "";
    $txtRetRH = "";
    while($objRow = myFetchObj($rsResult)){
        // form
        $txtAutokey = $objRow->Outlinekey;
        $txtRet .= "<div class=\"form-group row align-items-center\">";
        $txtRet .= "<label for=\"rawdata-$txtAutokey\" class=\"col-sm-4 col-md-12 col-form-label\">".$objRow->Description."</label>";
        $txtRet .= "<div class=\"col-sm-8 col-md-12\">";
        // Type: T-text, N-number, S-select, C-checkbox
        switch($objRow->Type){
            case "N":
                $txtRet .= "<input type=\"number\" id=\"rawdata-$txtAutokey\" class=\"form-control\">";
            break;
            // TODO: select and checkbox
            case "T":
            default:
                $txtRet .= "<input type=\"text\" id=\"rawdata-$txtAutokey\" class=\"form-control\">";
            break;
        }
        $txtRet .= "</div>";
        $txtRet .= "</div>";

        // report header
        $txtRetRH .= "<th class=\"text-center th-lg\">";
        $txtRetRH .= $objRow->Description;
        $txtRetRH .= "</th>";
    }
    return array($txtRet, $txtRetRH);
}

//for now only
if(!isset($_SESSION["currUserName"])){
    header("Location: ".$_SESSION["HomeDir"]."login");
    exit;
}
$boolFromLogin = true;
$_SESSION["currUserName"] = "jp";
$_SESSION["currPassword"] = "admin";
$intHandle = myOpenDB();

$txtDateToday = date("Y-m-d");
$arrDateToday = explode("-", $txtDateToday);
$txtDateFrom = date("Y-m-d", strtotime($txtDateToday."-9 days"));
$arrDateFrom = explode("-", $txtDateToday);

$strQ = "SELECT FormOutlineDetl.Autokey AS Outlinekey, FormOutlineDetl.`Description`, FormOutlineDetl.`Type` FROM FormOutlineDetl";
$strQ .= " INNER JOIN FormOutlineMain ON FormOutlineDetl.FormOutlinekey = FormOutlineMain.Autokey";
$strQ .= " INNER JOIN Stores ON FormOutlineMain.Storekey = Stores.Autokey";
$strQ .= " WHERE Stores.Autokey = '".$_SESSION["storeKey"]."'";
$strQ .= " ORDER BY FormOutlineDetl.SeqNo, FormOutlineDetl.Description";
// echo $strQ; exit;
$rsResult = mySelectDB($strQ, $intHandle);
list($txtStoreForm, $txtDailyRecordReportHeader) = myRenderStoreHTML($rsResult);



$txtDailyRecordReportContent = "";

?>