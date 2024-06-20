<?php
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

// Initialize variables from POST data
$filterType = isset($_POST['filterType']) ? $_POST['filterType'] : null;
$filterValue = isset($_POST['filterValue']) ? $_POST['filterValue'] : null;

// Initialize SQL statement and parameters based on filter type
$sql = "";
$params = [];
$types = "";

// Prepare SQL query based on the selected filter type
if ($filterType == 'day') {
    $sql = "SELECT * FROM `orders` WHERE DAYNAME(arrivaldate) = ?";
    $params[] = $filterValue;
    $types = "s";
} elseif ($filterType == 'month') {
    $sql = "SELECT * FROM `orders` WHERE MONTH(arrivaldate) = ?";
    $params[] = $filterValue;
    $types = "i";
} elseif ($filterType == 'year') {
    $sql = "SELECT * FROM `orders` WHERE YEAR(arrivaldate) = ?";
    $params[] = $filterValue;
    $types = "i";
} else {
    die("Invalid filter type selected.");
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["firstName"] . " " . $row["lastName"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["brand"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["model"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["spec"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["arrivaldate"]) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No quotes found for the selected filter.</td></tr>";
    }
} else {
    echo "<tr><td colspan='10'>Error executing query: " . $stmt->error . "</td></tr>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
