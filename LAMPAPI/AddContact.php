<?php
	$inData = getRequestInfo();

	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Phone = $inData["Phone"];
	$Email = $inData["Email"];

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

		//checks if in testing mode for swaggerhub for the source of userId. Either from session or input from swaggerhub
                $testingMode = isset($inData['testing']) && $inData['testing'] === true;

                if ($testingMode && isset($inData['userId'])) {
                $userId = $inData['userId'];
                } else if (isset($_SESSION['Users']) && isset($_SESSION['Users']['ID'])) {
                $userId = $_SESSION['Users']['ID'];
                }else {
                returnWithError("User ID not available in session or request body");
                }

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

	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>
