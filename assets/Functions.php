<?php

/*
	Functions commonly used of all pages
*/
session_start();
date_default_timezone_set('Asia/Manila');
include_once("Config.php");

function Error404($txtErrorMsg = ""){
	include_once("error/error.html.php");
}

function PageExecute($page = "home"){
	// this is the template for each page
	$arrFile = array(
		"controller/$page.php"=>"",
		"template/Header.php"=>"<code>Header</code> not found",
		"view/$page.php"=>"<code>View</code> not found",
		"template/Footer.php"=>"<code>Footer</code> not found"
	);
	foreach($arrFile as $file=>$errorMsg){
		if(file_exists($file)) include_once($file);
		else{
			Error404($errorMsg);
			break;
		}
	}
}

function myOpenDB(){
	$myPDO = new PDO("mysql:host=".$_SESSION["txtServer"].";dbname=".$_SESSION["txtMySQLDB"], $_SESSION["txtMySQLUser"], $_SESSION["txtMySQLPass"]);
	return $myPDO;
}

function myExecDB($strQ, $intHandle){
	if($intHandle){
		$result = $intHandle->query("$strQ");
		return $result;
	}
	else{
		return 0; // how to error?
	}
}

function mySelectDB($strQ, $intHandle){
	if($intHandle){
		$result = $intHandle->query("$strQ");
		return $result;
	}
	else{
		return 0; // how to error?
	}
}

function myInsertDB($strQ, $intHandle){
	if($intHandle){
		$result = $intHandle->exec("$strQ");
		return $intHandle->lastInsertId();
	}
	else{
		return 0; // how to error?
	}
}

function myUpdateDB($strQ, $intHandle){
	if($intHandle){
		$result = $intHandle->prepare($strQ);
		$result->execute();
		return $result->rowCount();
	}
	else{
		return 0; // how to error?
	}
}

function myDeleteDB($strQ, $intHandle){
	if($intHandle){
		$result = $intHandle->prepare($strQ);
		$result->execute();
		return $result->rowCount();
	}
	else{
		return 0; // how to error?
	}
}

function myFetchRow($rsResult){
	return $rsResult->fetch(PDO::FETCH_NUM);
}

function myNumRows($rsResult){
	return $rsResult->rowCount();
}

function myFetchObj($rsResult){
	return $rsResult->fetch(PDO::FETCH_OBJ);
}

?>