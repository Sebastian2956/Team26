<?php

    $inData = getRequestInfo();


	$conn = new mysqli("localhost", "Sebastian", "123456789", "ContactManager");
    if ($conn->connect_error)
    {
    returnWithError($conn->connect_error);
    }
    else
    {
        $CurrentUser = $_SESSION['Users'];
        $userId = $CurrentUser['ID'];

        // Delete contacts associated with the user
        $stmtContacts = $conn->prepare("DELETE FROM Contacts WHERE UserID = ?");
        $stmtContacts->bind_param("i", $userId);
        $stmtContacts->execute();
        $stmtContacts->close();

        // Delete the user
        $stmtUser = $conn->prepare("DELETE FROM Users WHERE ID = ?");
        $stmtUser->bind_param("i", $userId);
        $stmtUser->execute();

        // Check if the user was deleted
        if ($stmtUser->affected_rows > 0) {
            returnWithInfo("User and associated contacts deleted successfully");
        } else {
            returnWithError("User not found");
        }

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
