<?php

$intHandle = myOpenDB();

function FormOutlineRender($rsResult){
    $txtRet = "";
    while($objRow = myFetchObj($rsResult)){
        switch($objRow->Type){
            case "N":
                $tmpType = "Number";
            break;
            case "T":
            default:
                $tmpType = "Text";
            break;
        }

        $txtRet .= "<tr id=\"".$objRow->OutlineDetlkey."\">";
        $txtRet .= "<td>".$objRow->SeqNo."</td>";
        $txtRet .= "<td>".$objRow->Description."</td>";
        $txtRet .= "<td class=\"text-center\">".$tmpType."</td>";
        $txtRet .= "<td class=\"text-center\">".($objRow->IsWithGraph ? "YES":"NO")."</td>";

        // color render
        $txtRet .= "<td class=\"text-center align-middle\"><div style=\"height: 24px; width: 24px; display: inline-block; vertical-align:top; background: rgba(".$objRow->Red.",".$objRow->Green.",".$objRow->Blue.",0.2); border: 3px solid rgba(".$objRow->Red.",".$objRow->Green.",".$objRow->Blue.",1); border-radius: 50%;\"></div></td>";
        $txtRet .= "</tr>";
    }
    return $txtRet;
}

switch(strtolower($_GET["type"])){
    /**
     * saving
     */
    case "save":
        switch(strtolower(addslashes($_POST["txtSaveType"]))){
            case "store":
                // TODO
            break;
            case "account":
                $strQ = "UPDATE Users SET";
                if(isset($_POST["txtLastName"])) $strQ .= " LastName = '".addslashes(trim($_POST["txtLastName"]))."',";
                if(isset($_POST["txtFirstName"])) $strQ .= " FirstName = '".addslashes(trim($_POST["txtFirstName"]))."',";
                if(isset($_POST["txtMidName"])) $strQ .= " MidName = '".addslashes(trim($_POST["txtMidName"]))."',";
                if(isset($_POST["txtEmail"])) $strQ .= " Email = '".addslashes(trim($_POST["txtEmail"]))."',";
                if(isset($_POST["txtMobileNo"])) $strQ .= " MobileNo = '".addslashes(trim($_POST["txtMobileNo"]))."',";
                $strQ .= " LastEditBy = '".$_SESSION["currUserName"]."',";
                $strQ .= " LastEditDate = '".date("Y-m-d H:i:s")."'";
                $strQ .= " WHERE UserName = '".$_SESSION["currUserName"]."'";
                if(myUpdateDB($strQ, $intHandle)) $_SESSION["intSaveSuccess"] = 1;
                else $_SESSION["intSaveSuccess"] = 0;
                header("Location: ".$_SESSION["HomeDir"]."settings/".$_POST["txtSaveType"]);
            break;
            default:
                Error404();
            break;
        }
    break;
    case "store":
        $intStorekey = $_SESSION["storeKey"];
        $strQ = "SELECT StoreNo, `Description` FROM Stores where Autokey = '$intStorekey'";
        $rsResult = mySelectDB($strQ, $intHandle);
        list($txtStoreNo, $txtStoreDesc) = myFetchRow($rsResult);
        
        $strQ = "SELECT FormOutlineDetl.Autokey AS OutlineDetlkey, FormOutlineDetl.SeqNo, FormOutlineDetl.Description, FormOutlineDetl.Type, FormOutlineDetl.IsWithGraph, Colors.Red, Colors.Green, Colors.Blue FROM FormOutlineDetl INNER JOIN FormOutlineMain ON FormOutlineDetl.FormOutlinekey = FormOutlineMain.Autokey INNER JOIN Colors ON FormOutlineDetl.Colorkey = Colors.Autokey WHERE FormOutlineMain.Storekey = '$intStorekey' ORDER BY FormOutlineDetl.SeqNo, FormOutlineDetl.SeqNo";
        $txtFormOutline = FormOutlineRender(mySelectDB($strQ, $intHandle));
        
    break;

    case "account":
        $strQ = "SELECT LastName, FirstName, MidName, Email, MobileNo FROM Users WHERE UserName = '".$_SESSION["currUserName"]."'";
        $rsResult = mySelectDB($strQ, $intHandle);

        list($txtLastName, $txtFirstName, $txtMidName, $txtEmail, $txtMobileNo) = myFetchRow($rsResult);
    break;
}

?>