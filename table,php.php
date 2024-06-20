<?php
$dsn = 'mysql:host=127.0.0.1;dbname=lingscars';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully\n";

    // SQL statements to create tables
    $sqlMember = "
    CREATE TABLE IF NOT EXISTS Member (
        memberID INT(5) NOT NULL AUTO_INCREMENT,
        emailMember VARCHAR(50) NOT NULL,
        passMember VARCHAR(50) NOT NULL,
        PRIMARY KEY (memberID)
    )";

    $sqlVehicle = "
    CREATE TABLE IF NOT EXISTS Vehicle (
        vehicleID INT(5) NOT NULL AUTO_INCREMENT,
        vehicleBrand VARCHAR(200) NOT NULL,
        vehicleModel VARCHAR(200) NOT NULL,
        vehicleSpec VARCHAR(200) NOT NULL,
        vehiclePrice VARCHAR(100) NOT NULL,
        vehicleComponents VARCHAR(200) NOT NULL,
        category VARCHAR(10) NOT NULL,
        PRIMARY KEY (vehicleID)
    )";

    $sqlCart = "
    CREATE TABLE IF NOT EXISTS Cart (
        CartID INT(5) NOT NULL AUTO_INCREMENT,
        memberID INT(5) NOT NULL,
        firstName VARCHAR(200) NOT NULL,
        lastName VARCHAR(200) NOT NULL,
        address VARCHAR(150) NOT NULL,
        phoneNumber VARCHAR(15) NOT NULL,
        quantity INT(5) NOT NULL,
        totalPrice DECIMAL(10,2) NOT NULL,
        vehicleID INT(5) NOT NULL,
        PRIMARY KEY (CartID),
        KEY idx_memberID (memberID),
        KEY idx_vehicleID (vehicleID),
        FOREIGN KEY (memberID) REFERENCES Member(memberID),
        FOREIGN KEY (vehicleID) REFERENCES Vehicle(vehicleID)
    )";

    // Execute the SQL statements in the correct order
    $pdo->exec($sqlMember);
    $pdo->exec($sqlVehicle);
    $pdo->exec($sqlCart);

    echo "Tables created successfully";

} catch (PDOException $e) {
    echo "Connection failed: ". $e->getMessage();
}
?>