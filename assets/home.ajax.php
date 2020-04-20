<?php
/*
    This will do backend stuffs without refreshing the page
*/
include_once("../assets/Functions.php");
$intHandle = myOpenDB();
function SaveForm($intHandle){
    // global variables
    $arrIDList = explode("|", $_POST["postIDList"]);
    $arrValue = explode("|", $_POST["postValList"]);
    $txtRemarks = addslashes($_POST["postRemarks"]);
    $txtDate = addslashes(trim($_POST["postDate"]));

    $intStorekey = $_SESSION["storeKey"];
    $txtCurrUser = $_SESSION["currUserName"];

    // process
    $boolUpdated = false;
    $boolInserted = false;

    $strQ = "SELECT Autokey FROM DailyRecordMain WHERE Storekey = '$intStorekey' AND RecordDate = '$txtDate'";
    $rsResult = mySelectDB($strQ, $intHandle);
    $boolOldRecord = (myNumRows($rsResult) > 0 ? true : false);

    if($boolOldRecord){ // update main
        list($intDailyRecordkey) = myFetchRow($rsResult);

        $strQ = "UPDATE DailyRecordMain SET";
        $strQ .= " Remarks = '$txtRemarks',";
        $strQ .= " LastEditBy = '$txtCurrUser'";
        $strQ .= " WHERE Autokey = '$intDailyRecordkey'";
        
        if(myUpdateDB($strQ, $intHandle)) $boolUpdated = true;
    }
    else{ // insert detl
        $strQ = "INSERT INTO DailyRecordMain (Storekey, RecordDate, Remarks, CreatedBy, LastEditBy) VALUES ('$intStorekey', '$txtDate', '$txtRemarks', '$txtCurrUser', '$txtCurrUser')";
        $intDailyRecordkey = myInsertDB($strQ, $intHandle);
    }

    for($idx = 0; $idx < count($arrIDList); $idx++){
        $intOutlineDetlkey = addslashes(trim($arrIDList[$idx]));
        $txtValue = addslashes(trim($arrValue[$idx]));
        
        // define type
        $strQ = "SELECT `Type` FROM FormOutlineDetl WHERE Autokey = '$intOutlineDetlkey'";
        $rsResult = mySelectDB($strQ, $intHandle);
        list($txtLineType) = myFetchRow($rsResult);
        if($txtLineType == 'N' AND strlen($txtValue) == 0) $txtValue = 0;

        if($boolOldRecord){
            $strQ = "SELECT Autokey FROM DailyRecordDetl WHERE DailyRecordkey = '$intDailyRecordkey' AND OutlineDetlkey = '$intOutlineDetlkey'";
            $rsResultDetl = mySelectDB($strQ, $intHandle);
            $boolOldRecordDetl = (myNumRows($rsResultDetl) > 0 ? true : false);
        }
        else $boolOldRecordDetl = false;

        if($boolOldRecordDetl){ // update detl
            list($intDailyRecordDetlkey) = myFetchRow($rsResultDetl);
            $strQ = "UPDATE DailyRecordDetl SET";
            $strQ .= " RawData = '$txtValue',";
            $strQ .= " LastEditBy = '$txtCurrUser'";
            $strQ .= " WHERE Autokey = '$intDailyRecordDetlkey'";

            if(myUpdateDB($strQ, $intHandle)) $boolUpdated = true;
        }
        else{ // insert detl
            $strQ = "INSERT INTO DailyRecordDetl (DailyRecordkey, OutlineDetlkey, RawData, CreatedBy, LastEditBy) VALUES ('$intDailyRecordkey', '$intOutlineDetlkey', '$txtValue', '$txtCurrUser', '$txtCurrUser')";
            if(myInsertDB($strQ, $intHandle)) $boolInserted = true;
        }
    }

    if($boolInserted) return "1|Successfully saved the record.";
    elseif($boolUpdated) return "1|Successfully updated the record.";
    else return "0|Failed to save the record";
}

function GoForm($intHandle){
    $strQ = "SELECT DailyRecordMain.Remarks, DailyRecordDetl.OutlineDetlkey, DailyRecordDetl.RawData FROM DailyRecordDetl";
    $strQ .= " INNER JOIN DailyRecordMain ON DailyRecordDetl.DailyRecordkey = DailyRecordMain.Autokey";
    $strQ .= " INNER JOIN FormOutlineDetl ON DailyRecordDetl.OutlineDetlkey = FormOutlineDetl.Autokey";
    $strQ .= " WHERE DailyRecordMain.Storekey = '".$_SESSION["storeKey"]."' AND DailyRecordMain.RecordDate = '".$_POST["postDate"]."' ORDER BY FormOutlineDetl.SeqNo, FormOutlineDetl.Description";
    $rsResult = mySelectDB($strQ, $intHandle);

    $txtRet = "";
    $txtRemarks = "";
    while($objRow = myFetchObj($rsResult)){
        if(strlen($txtRemarks) == 0) $txtRemarks = $objRow->Remarks;
        $txtRet .= "|".$objRow->OutlineDetlkey."~".$objRow->RawData;
    }
    return $txtRemarks.$txtRet;
}

function GoChart($intHandle){
    $txtDateTo = addslashes($_POST["postDateTo"]);
    $txtDateFrom = addslashes($_POST["postDateFrom"]);

    // hard code key to graph: load sales
    $strQ = "SELECT FormOutlineDetl.Autokey FROM FormOutlineDetl INNER JOIN FormOutlineMain ON FormOutlineDetl.FormOutlinekey = FormOutlineMain.Autokey WHERE FormOutlineMain.Storekey = '".$_SESSION["storeKey"]."' AND FormOutlineDetl.IsWithGraph = 1";
    $rsResult = mySelectDB($strQ, $intHandle);
    while($objRow = myFetchObj($rsResult)){
        $arrOutlineDetlkey[] = $objRow->Autokey;
    }
    $txtOutlineDetlkey = implode(",", $arrOutlineDetlkey);

    $strQ = "SELECT FormOutlineDetl.Description, Colors.Red, Colors.Green, Colors.Blue, DailyRecordMain.RecordDate, DailyRecordDetl.RawData FROM DailyRecordMain";
    $strQ .= " INNER JOIN DailyRecordDetl ON DailyRecordMain.Autokey = DailyRecordDetl.DailyRecordkey";
    $strQ .= " INNER JOIN FormOutlineDetl ON DailyRecordDetl.OutlineDetlkey = FormOutlineDetl.Autokey";
    $strQ .= " INNER JOIN Colors ON FormOutlineDetl.Colorkey = Colors.Autokey";
    $strQ .= " WHERE FormOutlineDetl.Autokey IN ($txtOutlineDetlkey) AND DailyRecordMain.RecordDate >= '$txtDateFrom' AND DailyRecordMain.RecordDate <= '$txtDateTo'";
    $strQ .= " ORDER BY FormOutlineDetl.SeqNo, FormOutlineDetl.Description, DailyRecordMain.RecordDate";
    $rsResult = mySelectDB($strQ, $intHandle);
    
    $arrReturn = array();
    $arrReturn["type"] = "line";
    $tmpDesc = "";
    $idx = -1;
    while($objRow = myFetchObj($rsResult)){
        if($tmpDesc != $objRow->Description){
            $tmpDesc = $objRow->Description;
            $idx++;
            $arrReturn["data"]["datasets"][$idx]["label"] = $tmpDesc;
            $r = $objRow->Red;
            $g = $objRow->Green;
            $b = $objRow->Blue;
            $arrReturn["data"]["datasets"][$idx]["backgroundColor"] = "rgba($r, $g, $b, 0.2)";
            $arrReturn["data"]["datasets"][$idx]["borderColor"] = "rgba($r, $g, $b, 1)";
        }
        if(!$idx) $arrReturn["data"]["labels"][] = str_replace("-", "/", substr($objRow->RecordDate, -5));
        $arrReturn["data"]["datasets"][$idx]["data"][] = $objRow->RawData;
    }
    
    return json_encode($arrReturn);
}

function GoReport($intHandle){
    $intLoadLimit = 0 + (int)$_POST["postLoadLimit"];
    $txtLastDateRecord = addslashes(trim($_POST["postLastDateRecord"]));

    // hard code key to graph: load sales
    $strQ = "SELECT FormOutlineDetl.Autokey FROM FormOutlineDetl INNER JOIN FormOutlineMain ON FormOutlineDetl.FormOutlinekey = FormOutlineMain.Autokey WHERE FormOutlineMain.Storekey = '".$_SESSION["storeKey"]."' AND FormOutlineDetl.IsWithGraph = 1";
    $rsResult = mySelectDB($strQ, $intHandle);
    while($objRow = myFetchObj($rsResult)){
        $arrOutlineDetlkey[] = $objRow->Autokey;
    }
    $txtOutlineDetlkey = implode(",", $arrOutlineDetlkey);

    $strQ = "SELECT DailyRecordMain.RecordDate, DailyRecordDetl.RawData, FormOutlineDetl.SeqNo FROM DailyRecordMain";
    $strQ .= " INNER JOIN DailyRecordDetl ON DailyRecordMain.Autokey = DailyRecordDetl.DailyRecordkey";
    $strQ .= " INNER JOIN FormOutlineDetl ON DailyRecordDetl.OutlineDetlkey = FormOutlineDetl.Autokey";
    $strQ .= " INNER JOIN Colors ON FormOutlineDetl.Colorkey = Colors.Autokey";
    $strQ .= " WHERE FormOutlineDetl.Autokey IN ($txtOutlineDetlkey)";
    if(strlen($txtLastDateRecord)) $strQ .= " AND DailyRecordMain.RecordDate < '$txtLastDateRecord'";
    $strQ .= " ORDER BY DailyRecordMain.RecordDate DESC, FormOutlineDetl.SeqNo, FormOutlineDetl.Description";
    $strQ .= " LIMIT ".count($arrOutlineDetlkey)*$intLoadLimit;
    $arrReturn["Q"] = $strQ;
    $rsResult = mySelectDB($strQ, $intHandle);

    $idx = -1;
    $tmpDate = "";
    $arrReturn["RecordDate"] = [];
    while($objRow = myFetchObj($rsResult)){
        if($tmpDate != $objRow->RecordDate){
            $idx++;
            if($idx == $intLoadLimit) break;

            $tmpDate = $objRow->RecordDate;
            $arrReturn["RecordDate"][$idx] = date("F d, Y", strtotime($tmpDate));
            $arrReturn["txtLastDateRecord"] = $tmpDate;
        }
        $arrReturn["SeqNo"][$idx][] = $objRow->SeqNo;
        $arrReturn["RawData"][$idx][] = number_format($objRow->RawData, 2);
    }

    return json_encode($arrReturn);
}

switch($_POST["postAction"]){
    case "SaveForm":
        echo SaveForm($intHandle);
    break;
    case "GoForm":
        echo GoForm($intHandle);
    break;
    case "GoChart":
        echo GoChart($intHandle);
    break;
    case "GoReport":
        echo GoReport($intHandle);
    break;
}

?>
