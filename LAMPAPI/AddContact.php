<?php
	$inData = getRequestInfo();
	
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Phone = $inData["Phone"];
	$Email = $inData["Email"];

	//Do not think that this is needed but will keep
	$userId = $inData["userId"]; #still need a way to get the current user's id to assign contact to this user

	$conn = new mysqli("localhost", "Sebastian", "123456789", "ContactManager");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{


		if($_POST[$FirstName] =='' || $_POST[$LastName] =='' || $_POST[$Phone] =='' || $_POST[$Email] =='')
		{
			returnWithError("One or more fields not filled out");
		}

		//gets the current users ID
		$CurrentUser = $_SESSION['Users'];
		$userId = $CurrentUser['ID'];


		$stmt = $conn->prepare("INSERT into Contacts (FirstName, LastName, Phone, Email, UserID) VALUES(?,?,?,?,?)");
		$stmt->bind_param("ssssi", $FirstName, $LastName, $Phone, $Email, $userId);
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