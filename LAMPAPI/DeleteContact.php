<?php

  $inData = getRequestInfo();

  $UserID = $inData["id"];


	$conn = new mysqli("localhost", "Sebastian", "123456789", "ContactManager");
  if ($conn->connect_error)
  {
    returnWithError($conn->connect_error);
  }
  else
  {

    //this is going to be needed to remove the contacts from the specific user's account
		//gets the current users ID
  //   $CurrentUser = $_SESSION['Users'];
		// $userId = $CurrentUser['ID'];

    $stmt = $conn->prepare("DELETE FROM Contacts WHERE id = (UserID) VALUES(?)");
    $stmt->bind_param("i", $UserID);
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
