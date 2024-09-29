<?php
	$inData = getRequestInfo();
<<<<<<< HEAD
	
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Login = $inData["Login"];
	$Password = $inData["Password"];

	$conn = new mysqli("team26cm.seb.christmas/Team26", "Sebastian", "123456789", "ContactManager");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("INSERT into Users (FirstName, LastName, Login, Password) VALUES(?,?,?,?)");
		$stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
	}
=======


	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Login = $inData["login"];
	$Password = $inData["password"];

  //TODO: This needs to be updated with the correct connection point
	$conn = new mysqli("localhost", "root", "", "ContactManager");


	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{

		if(empty($FirstName) || empty($LastName) || empty($Login) || empty($Password))
		{
			returnWithError("One or more fields not filled out");
		}


		// Check if the Login already exists
		$checkLoginStmt = $conn->prepare("SELECT * FROM Users WHERE Login = ?");
		$checkLoginStmt->bind_param("s", $Login);
		$checkLoginStmt->execute();
		$result = $checkLoginStmt->get_result();

		// If the login exists, return an error
		if ($result->num_rows > 0)
		{
			returnWithError("Login name already exists");
		}
		else
		{
			$stmt = $conn->prepare("INSERT into Users (FirstName, LastName, Login, Password) VALUES(?,?,?,?)");
			$stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password);
			if ($stmt->execute()) {
				returnWithError(""); // Successful insert, no error
			} else {
				returnWithError("Error inserting user");
			}
			$stmt->close();
		}
		$checkLoginStmt->close();
	}
	$conn->close();
>>>>>>> main

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
