<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "lingscars";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT CONCAT(YEAR(arrivaldate), '-', MONTH(arrivaldate)) AS month, COUNT(*) AS total 
        FROM `Orders` 
        WHERE YEAR(arrivaldate) = YEAR(CURDATE()) 
        GROUP BY YEAR(arrivaldate), MONTH(arrivaldate)";
$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labels[] = $row["month"];
        $data[] = $row["total"];
    }
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);

$conn->close();
?>
