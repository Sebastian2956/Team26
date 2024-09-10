<?php
	$inData = getRequestInfo();
	
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Phone = $inData["Phone"];
	$Email = $inData["Email"];

	$OldPhone = $inData["Phone"];

	$conn = new mysqli("localhost", "Sebastian", "123456789", "ContactManager");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{

        //get current user ID
        $CurrentUser = $_SESSION['Users'];
		$userId = $CurrentUser['ID'];
        
        //get which contact you want changed
		$stmt = $conn->prepare("select FirstName from Contacts where Phone like ? and UserID=?");
		$OldPhone = "%" . $inData["search"] . "%";
		$stmt->bind_param("ss", $OldPhone, $inData["userId"]);
		$stmt->execute();
		
		$result = $stmt->get_result();


        //alter specfic user with all new info given
        #in the future could replace individual parts rather then full person
		$stmt = $conn->prepare("ALTER Contacts $result ");
		$stmt->execute();
        $stmt = $conn->prepare("WITH Contacts INSERT into Contacts (FirstName, LastName, Phone, Email, UserID) VALUES(?,?,?,?,?) ");
        $stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password,);
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