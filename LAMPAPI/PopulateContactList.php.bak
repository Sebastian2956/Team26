<?php

	$inData = getRequestInfo();
  //TODO: This needs to be updated with the correct connection point
	$conn = new mysqli("localhost", "root", "", "ContactManager");
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		//requests the database for the contacts of the session user and returns it
		$array = array();
		$stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID=?;");
		$stmt->bind_param("i", $inData['userId']);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc()) {
			$array[] = $row;
		}
		//This echo is how we have been returning the data so far
		echo json_encode($array);

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

	function returnWithInfo( $FirstName, $LastName, $id )
	{
		$retValue = '{"id":' . $id . ',"FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>
