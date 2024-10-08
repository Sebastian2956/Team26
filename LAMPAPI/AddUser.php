<?php
	$inData = getRequestInfo();


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
