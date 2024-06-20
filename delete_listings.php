<?php
// Database connection parameters
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "lingscars_admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $adminID = $_POST['adminID']; 
    $vehicleID = $_POST['vehicleID'];

    // Check if admin ID exists
    $adminCheckSql = "SELECT * FROM Administrator WHERE adminID = ?";
    $stmt = $conn->prepare($adminCheckSql);
    $stmt->bind_param("i", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Prepare delete statement
        $sql = "DELETE FROM Vehicle WHERE vehicleID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vehicleID);

        if ($stmt->execute()) {
            echo "<script>
                    alert('You successfully deleted a vehicle listing!');
                    window.location.href = 'listings.html';
                  </script>";
        } else {
            echo "<script>
                    alert('An error occurred while deleting the listing. Please try again later.');
                    window.history.back();
                  </script>";
        }
    } else {
        // Admin ID does not exist
        echo "<script>
                alert('Admin ID does not exist. Please enter a valid Admin ID.');
                window.history.back();
              </script>";
    }
}

// Close connection
$conn->close();
?>
