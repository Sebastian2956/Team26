<?php

	$inData = getRequestInfo();

	$contactFirstName = $inData['contactFirstName'];
	$contactLastName = $inData['contactLastName'];


  	//TODO: This needs to be updated with the correct connection point
	$conn = new mysqli("localhost", "root", "", "ContactManager");
	if ($conn->connect_error)
	{
		returnWithError($conn->connect_error);
	}
	else
	{
		$userId = $inData['userId'];

		//get the contact id
		$stmtId = $conn->prepare("Select ID from Contacts where FirstName = ? AND LastName = ? AND UserId = ?");
		$stmtId->bind_param("ssi", $inData["contactFirstName"], $inData["contactLastName"], $userId);
		$stmtId->execute();
		$contactId = $stmtId->get_result()->fetch_assoc()['ID'];

		$stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");
		$stmt->bind_param("i", $contactId);
		$stmt->execute();
		if ($stmt->affected_rows > 0) {
			returnWithError("");  // Success, return no error
		} else {
			returnWithError("Failed to delete contact");
		}
		$stmtId->close();
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
		echo json_encode($obj);
	}
	function returnWithError( $err )
	{
		sendResultInfoAsJson( $err );
	}

?>
