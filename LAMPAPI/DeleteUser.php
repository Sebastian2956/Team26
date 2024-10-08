<?php

    $inData = getRequestInfo();


  //TODO: This needs to be updated with the correct connection point
	$conn = new mysqli("localhost", "root", "", "ContactManager");
    if ($conn->connect_error)
    {
    returnWithError($conn->connect_error);
    }
    else
    {
        $userId = $inData['userId'];

        // Delete contacts associated with the user
        $stmtContacts = $conn->prepare("DELETE FROM Contacts WHERE UserID = ?");
        $stmtContacts->bind_param("i", $userId);
        $stmtContacts->execute();
        $stmtContacts->close();

        // Delete the user
        $stmtUser = $conn->prepare("DELETE FROM Users WHERE ID = ?");
        $stmtUser->bind_param("i", $userId);
        $stmtUser->execute();
        $stmtUser->close();
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
