<?php

switch(strtolower($_GET["type"])){
    case "store":
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
        
        $intHandle = myOpenDB();
        
        $intStorekey = $_SESSION["storeKey"];
        $strQ = "SELECT StoreNo, `Description` FROM Stores where Autokey = '$intStorekey'";
        $rsResult = mySelectDB($strQ, $intHandle);
        list($txtStoreNo, $txtStoreDesc) = myFetchRow($rsResult);
        
        $strQ = "SELECT FormOutlineDetl.Autokey AS OutlineDetlkey, FormOutlineDetl.SeqNo, FormOutlineDetl.Description, FormOutlineDetl.Type, FormOutlineDetl.IsWithGraph, Colors.Red, Colors.Green, Colors.Blue FROM FormOutlineDetl INNER JOIN FormOutlineMain ON FormOutlineDetl.FormOutlinekey = FormOutlineMain.Autokey INNER JOIN Colors ON FormOutlineDetl.Colorkey = Colors.Autokey WHERE FormOutlineMain.Storekey = '$intStorekey' ORDER BY FormOutlineDetl.SeqNo, FormOutlineDetl.SeqNo";
        $txtFormOutline = FormOutlineRender(mySelectDB($strQ, $intHandle));
        
    break;
}

?>