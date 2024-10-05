<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $inData = getRequestInfo();

    // Log input for debugging
    error_log("Received Contact ID: " . $inData["contactId"]);
    error_log("Received User ID: " . $inData["userId"]);

    // Retrieve contactId and userId from the request
    $contactId = $inData["contactId"];
    $userId = $inData["userId"];

    $conn = new mysqli("localhost:3307", "root", "", "ContactManager");
    if ($conn->connect_error) {
        error_log("Connection Error: " . $conn->connect_error);  // Log connection error
        returnWithError($conn->connect_error);
    } else {
        // Log successful connection
        error_log("Database connection successful.");

        // Prepare a query to fetch the contact by contactId and userId
        $stmt = $conn->prepare("SELECT * FROM Contacts WHERE ID=? AND UserID=?;");
        $stmt->bind_param("ii", $contactId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Log the query results
        if ($row = $result->fetch_assoc()) {
            error_log("Fetched contact: " . json_encode($row));  // Log fetched contact
            sendResultInfoAsJson($row);  // Send the single contact as JSON
        } else {
            error_log("No contact found or unauthorized access.");  // Log failure
            returnWithError("No contact found.");
        }

        $stmt->close();
        $conn->close();
    }

    // Corrected function to decode request input
    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);  // Fixed syntax error here
    }

    // Function to send JSON response
    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo json_encode($obj);
    }

    // Function to return error message in JSON
    function returnWithError($err) {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
