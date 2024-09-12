<?php
// 1. Capture Form Data Using $_POST
//                              Triple equals means same data and type
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the values submitted via the form
    //the $_POST superglobal variable is the post request and you can access the data submitted
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // 2. Connect to the MySQL Database
    $servername = "localhost";
    $username = "cm_user";   // MySQL user
    $password = "password";  // MySQL password
    $dbname = "ContactManager";  // MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 3. Error Handling for Database Connection
    if ($conn->connect_error) { //the . is the string cat operator
                                //$conn is the server connection object and access it's properties and elements are accessed this way'
        die("Connection failed: " . $conn->connect_error);
    }

    // 4. Insert Data into the Database (Using Prepared Statements to Prevent SQL Injection)
    // This is a prepared statement, you write the SQL request and then assign placeholder values
    $stmt = $conn->prepare("INSERT INTO contacts (firstName, lastName, email, phone) VALUES (?, ?, ?, ?)");
    //then you define the data types and values
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $phone);  // "ssss" represents 4 string values

    // 5. Execute the Query and Handle Success/Failure
    if ($stmt->execute()) {
        echo "New contact added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // 6. Close the Statement and the Connection
    $stmt->close();
    $conn->close();
} else {
    // If the script is accessed via a method other than POST
    echo "Invalid request method.";
}
?>

