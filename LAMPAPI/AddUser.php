<?php
	$inData = getRequestInfo();
	

	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Login = $inData["login"];
	$Password = $inData["password"];

	$conn = new mysqli("localhost", "root", "", "ContactManager");

	
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{

		if($_POST[$FirstName] =='' || $_POST[$LastName] =='' || $_POST[$Login] =='' || $_POST[$Password] =='')
		{
			returnWithError("One or more fields not filled out");
		}


		$stmt = $conn->prepare("INSERT into Users (FirstName, LastName, Login, Password) VALUES(?,?,?,?)");
		$stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
?>