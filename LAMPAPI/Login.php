<?php

<<<<<<< HEAD
	$inData = getRequestInfo();
	
	$id = 0;
	$FirstName = "";
	$LastName = "";

	$conn = new mysqli("team26cm.seb.christmas/Team26/", "Sebastian", "123456789", "ContactManager"); 	
=======

	//this receives the data from the code.js function, (doLogin())
	$inData = getRequestInfo();

	$id = 0;
	$FirstName = "";
	$LastName = "";

  	//TODO: This needs to be updated with the correct connection point
	$conn = new mysqli("localhost", "root", "", "ContactManager");
>>>>>>> main
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
<<<<<<< HEAD
=======
    	//preparing an sql statement
>>>>>>> main
		$stmt = $conn->prepare("SELECT ID,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["login"], $inData["password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
			returnWithInfo( $row['FirstName'], $row['LastName'], $row['ID'] );
		}
		else
		{
			returnWithError("No Records Found");
		}

		$stmt->close();
		$conn->close();
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
		$retValue = '{"id":0,"FirstName":"","LastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
<<<<<<< HEAD
	
=======

>>>>>>> main
	function returnWithInfo( $FirstName, $LastName, $id )
	{
		$retValue = '{"id":' . $id . ',"FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
