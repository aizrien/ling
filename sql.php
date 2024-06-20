<?php
$dsn = 'mysql:host=127.0.0.1';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS lingscar");
    echo "Database created successfully\n";

    // Connect to the newly created database
    $dsn = 'mysql:host=127.0.0.1;dbname=lingscar';
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully\n";

    // SQL statements to create tables
    $sqlAdministrator = "
    CREATE TABLE IF NOT EXISTS Administrator (
        adminID INT(5) NOT NULL AUTO_INCREMENT,
        emailAdmin VARCHAR(50) NOT NULL,
        passAdmin VARCHAR(50) NOT NULL,
        PRIMARY KEY (adminID)
    )";

    $sqlMember = "
    CREATE TABLE IF NOT EXISTS Member (
        memberID INT(5) NOT NULL AUTO_INCREMENT,
        emailMember VARCHAR(50) NOT NULL,
        passMember VARCHAR(50) NOT NULL,
        PRIMARY KEY (memberID)
    )";

    $sqlVehicle = "
    CREATE TABLE IF NOT EXISTS Vehicle (
        vehicleID INT AUTO_INCREMENT PRIMARY KEY,
        adminID INT(5) NOT NULL,
        vehicleType VARCHAR(50) NOT NULL,
        vehicleName VARCHAR(100) NOT NULL,
        vehicleBrand VARCHAR(100) NOT NULL,
        vehicleModel VARCHAR(100) NOT NULL,
        vehicleSpec VARCHAR(255) NOT NULL,
        vehiclePrice VARCHAR(50) NOT NULL,
        FOREIGN KEY (adminID) REFERENCES Administrator(adminID)
    )";

    $sqlQuote = "
    CREATE TABLE IF NOT EXISTS Quote (
        quoteID INT(5) NOT NULL AUTO_INCREMENT,
        memberID INT(5) NOT NULL,
        firstName VARCHAR(200) NOT NULL,
        lastName VARCHAR(200) NOT NULL,
        address VARCHAR(150) NOT NULL,
        phoneNumber VARCHAR(15) NOT NULL,
        vehicleID INT(5) NOT NULL,
        PRIMARY KEY (quoteID),
        FOREIGN KEY (memberID) REFERENCES Member(memberID),
        FOREIGN KEY (vehicleID) REFERENCES Vehicle(vehicleID)
    )";

    // Execute the SQL statements to create tables
    $pdo->exec($sqlAdministrator);
    $pdo->exec($sqlVehicle);
    $pdo->exec($sqlMember);
    $pdo->exec($sqlQuote);
    echo "Tables created successfully\n";

    // Insert a new admin
$emailAdmin = 'lingscar@admin.com';
$passAdmin = 'lingling';

// Get the last adminID to determine the next adminID
$sqlLastAdminID = "SELECT adminID FROM Administrator ORDER BY adminID DESC LIMIT 1";
$stmtLastAdminID = $pdo->query($sqlLastAdminID);
$lastAdminID = $stmtLastAdminID->fetchColumn();

// Extract the numeric part, increment it, and format it to adminID
$nextAdminID = 'admin' . strval(intval(substr($lastAdminID, 5)) + 1);

// If there's no existing adminID, start with admin1001
if ($lastAdminID === false) {
    $nextAdminID = 'admin1001';
}

$sql = "INSERT INTO Administrator (adminID, emailAdmin, passAdmin) VALUES (:adminID, :emailAdmin, :passAdmin)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':adminID', $nextAdminID);
$stmt->bindParam(':emailAdmin', $emailAdmin);
$stmt->bindParam(':passAdmin', $passAdmin);

if ($stmt->execute()) {
    echo "New administrator added successfully.";
} else {
    echo "Failed to add new administrator.";
}


} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
