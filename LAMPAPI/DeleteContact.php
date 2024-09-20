<?php

	$inData = getRequestInfo();

	$contactName = $inData["contactName"];


  	//TODO: This needs to be updated with the correct connection point
	$conn = new mysqli("localhost", "Sebastian", "123456789", "ContactManager");
	if ($conn->connect_error)
	{
		returnWithError($conn->connect_error);
	}
	else
	{
		//checks if in testing mode for swaggerhub for the source of userId. Either from session or input from swaggerhub
		$testingMode = isset($inData['testing']) && $inData['testing'] === true;

		if ($testingMode && isset($inData['userId'])) {
		$userId = $inData['userId'];
		} else if (isset($_SESSION['Users']) && isset($_SESSION['Users']['ID'])) {
		$userId = $_SESSION['Users']['ID'];
		}else {
		returnWithError("User ID not available in session or request body");
		}

		//get the contact id
		$stmtId = $conn->prepare("Select ID from Contacts where FirstName = ? AND LastName = ? AND UserId = ?");
		$stmtId->bind_param("ssi", $inData["contactFirstName"], $inData["contactLastName"], $userId);
		$stmtId->execute();
		$contactId = $stmtId->get_result()->fetch_assoc()['ID'];

		$stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");
		$stmt->bind_param("i", $contactId);
		$stmt->execute();
		$stmtId->close();
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>
