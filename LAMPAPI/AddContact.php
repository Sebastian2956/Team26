<?php
<<<<<<< HEAD
	$inData = getRequestInfo();
	
=======
	session_start();
	$inData = getRequestInfo();

>>>>>>> main
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Phone = $inData["Phone"];
	$Email = $inData["Email"];
<<<<<<< HEAD
	$userId = $inData["userId"]; #still need a way to get the current user's id to assign contact to this user

	$conn = new mysqli("team26cm.seb.christmas/Team26", "Sebastian", "123456789", "ContactManager");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
=======
	$userId = $inData["userId"];

  //TODO: This needs to be updated with the correct connection point
	$conn = new mysqli("localhost", "root", "", "ContactManager");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{


		if(empty($FirstName) || empty($LastName) || empty($Phone) || empty($Email))
		{
			returnWithError("One or more fields not filled out");
		}

                $userId = $inData['userId'];

>>>>>>> main
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
<<<<<<< HEAD
	
=======

>>>>>>> main
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
<<<<<<< HEAD
	
?>
=======

?>
>>>>>>> main
