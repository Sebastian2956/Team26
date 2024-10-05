<?php
	$inData = getRequestInfo();

	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Phone = $inData["Phone"];
	$Email = $inData["Email"];
	$oldFirstName = $inData["oldFirstName"];
	$oldLastName = $inData["oldLastName"];

	//TODO: this connection will have to be updated
	$conn = new mysqli("localhost:3307", "root", "", "ContactManager");
	if ($conn->connect_error)
	{
		returnWithError($conn->connect_error);
	}
	else
	{
		$userId = $inData['userId'];

		// Get ID of contact you want changed
		$stmt = $conn->prepare("SELECT ID FROM Contacts WHERE FirstName = ? AND LastName = ? AND UserID = ?");
		$stmt->bind_param("ssi", $oldFirstName, $oldLastName, $userId);
		$stmt->execute();
		$result = $stmt->get_result();
		$result_row = $result->fetch_assoc();
		$contactId = $result_row['ID'];

		// If contact ID exists, proceed to update
		if ($contactId) {
			// Prepare update query
			$stmt = $conn->prepare("UPDATE Contacts SET FirstName = ?, LastName = ?, Phone = ?, Email = ? WHERE ID = ?");
			// Bind the parameters (s = string, i = integer)
			$stmt->bind_param("ssssi", $FirstName, $LastName, $Phone, $Email, $contactId);
			
			// Execute update query
			if ($stmt->execute()) {
				// If update successful, return success message
				returnWithSuccess();
			} else {
				// If there's an error during update, return error
				returnWithError("Failed to update contact.");
			}

			$stmt->close();
		} else {
			// If contact ID not found, return error
			returnWithError("Contact not found.");
		}

		$conn->close();
	}

	// Function to handle request info
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	// Function to send JSON response
	function sendResultInfoAsJson($obj)
	{
		header('Content-type: application/json');
		echo json_encode($obj);
	}

	// Function to return an error message
	function returnWithError($err)
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson($retValue);
	}

	// Function to return success (no error)
	function returnWithSuccess()
	{
		$retValue = '{"error":""}';
		sendResultInfoAsJson($retValue);
	}

?>
