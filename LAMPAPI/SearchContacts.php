<?php

	$inData = getRequestInfo();
<<<<<<< HEAD
	
	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli("team26cm.seb.christmas/Team26", "Sebastian", "123456789", "ContactManager");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("select FirstName from Contacts where FirstName like ? and UserID=?");
		$FirstName = "%" . $inData["search"] . "%";
		$stmt->bind_param("ss", $FirstName, $inData["userId"]);
		$stmt->execute();
		
		$result = $stmt->get_result();
		
		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '"' . $row["FirstName"] . ' ' . $row["LastName"] . ' ' . $row["Phone"] . ' ' . $row["Email"] . '"';
		}
		
		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
		}
		
=======

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
>>>>>>> main
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
<<<<<<< HEAD
	
=======

>>>>>>> main
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
<<<<<<< HEAD
	
=======

>>>>>>> main
	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
<<<<<<< HEAD
	
?>
=======

?>
>>>>>>> main
