<?php
	$inData = getRequestInfo();

	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Login = $inData["Login"];
	$Password = $inData["Password"];

	//TODO: this connection will have to be updated
	$conn = new mysqli("localhost", "Sebastian", "123456789", "ContactManager");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{

        //get current user ID
        $CurrentUser = $_SESSION['Users'];
		$userId = $CurrentUser['ID'];

        //alter current user with all new info given
        #in the future could replace individual parts rather then full person
		$stmt = $conn->prepare("ALTER Users $userId ");
		$stmt->execute();
        $stmt = $conn->prepare("WITH Users (FirstName, LastName, Login, Password) VALUES(?,?,?,?) ");
        $stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password,);
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
