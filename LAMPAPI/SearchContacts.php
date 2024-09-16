<?php

	$inData = getRequestInfo();

	$searchElement = "";
	$userId = 0;

	//TODO: this connection will have to be updated
	$conn = new mysqli("localhost", "root", "", "ContactManager");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$searchElement = $inData["searchElement"];
		$array = array();
		//gets results from first and last name
		$stmt = $conn->prepare("SELECT * FROM Contacts WHERE (FirstName LIKE ? AND UserID=?) OR (LastName LIKE ? AND UserID=?);");
		$searchElement = "%" . $inData["searchElement"] . "%";
		$stmt->bind_param("sisi", $searchElement, $inData["userId"], $searchElement, $inData["userId"]);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc())
		{
			$array[] = $row;
		}

		sendResultInfoAsJson(json_encode($array));
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
