<?php
// Database connection parameters
$servername = "localhost";
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
    $attribute = $_POST['attribute'];
    $newValue = $_POST['newValue'];

    // Check if admin ID exists
    $adminCheckSql = "SELECT * FROM Administrator WHERE adminID = ?";
    $stmt = $conn->prepare($adminCheckSql);
    $stmt->bind_param("i", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Admin exists, proceed with updating vehicle details
        // Prepare update statement
        $sql = "UPDATE Vehicle SET $attribute = ? WHERE vehicleID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newValue, $vehicleID);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Vehicle details updated successfully.');
                    window.location.href = 'listings.html';
                  </script>";
        } else {
            echo "Error updating vehicle details: " . $conn->error;
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
