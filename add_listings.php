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
    // Get form data with error checking
    $adminID = isset($_POST['adminID']) ? $_POST['adminID'] : null;
    $vehicleType = isset($_POST['vehicleType']) ? $_POST['vehicleType'] : null;
    $vehicleName = isset($_POST['vehicleName']) ? $_POST['vehicleName'] : null;
    $vehicleBrand = isset($_POST['vehicleBrand']) ? $_POST['vehicleBrand'] : null;
    $vehicleModel = isset($_POST['vehicleModel']) ? $_POST['vehicleModel'] : null;
    $vehicleSpec = isset($_POST['vehicleSpec']) ? $_POST['vehicleSpec'] : null;
    $vehiclePrice = isset($_POST['vehiclePrice']) ? $_POST['vehiclePrice'] : null;

    // Check if all required fields are provided
    if (!$adminID || !$vehicleType || !$vehicleName || !$vehicleBrand || !$vehicleModel || !$vehicleSpec || !$vehiclePrice) {
        die("Please fill in all required fields.");
    }

    // Check if admin ID exists
    $adminCheckSql = "SELECT * FROM Administrator WHERE adminID = ?";
    $stmt = $conn->prepare($adminCheckSql);
    $stmt->bind_param("i", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Admin ID exists, proceed with adding the listing
        $insertSql = "INSERT INTO Vehicle (adminID, vehicleType, vehicleName, vehicleBrand, vehicleModel, vehicleSpec, vehiclePrice) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("issssss", $adminID, $vehicleType, $vehicleName, $vehicleBrand, $vehicleModel, $vehicleSpec, $vehiclePrice);
        if ($stmt->execute()) {
            echo "<script>
                    alert('You successfully added a vehicle listing!');
                    window.location.href = 'listings.html';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // Admin ID does not exist
        echo "<script>
                alert('Admin ID does not exist. Please enter a valid Admin ID.');
                window.history.back();
              </script>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
