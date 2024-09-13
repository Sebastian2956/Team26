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

        $stmt = $conn->prepare("DELETE FROM Users WHERE id = ?");
        $stmt->bind_param("i", $UserId);
        $stmt->execute();

        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            returnWithInfo("User deleted successfully");
        } else {
            returnWithError("No user found with the given ID");
        }
        
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

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
