<?php
// Database connection parameters
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "lingscars";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if memberID is set
if (isset($_POST['memberID'])) {
    $memberID = $_POST['memberID'];

    // Fetch the unhashed password securely
    $sql = "SELECT passMember FROM member WHERE memberID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $memberID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['passMember'];
    } else {
        echo "No password found";
    }

    $stmt->close();
}

$conn->close();
?>
