<?php
$dsn = 'mysql:host=127.0.0.1';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the databases if they don't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS lingscars");
    echo "Database 'lingscars' created successfully\n";

    $pdo->exec("CREATE DATABASE IF NOT EXISTS lingscars_admin");
    echo "Database 'lingscars_admin' created successfully\n";

    // Connect to the lingscars database
    $dsnLingscars = 'mysql:host=127.0.0.1;dbname=lingscars';
    $pdoLingscars = new PDO($dsnLingscars, $username, $password);
    $pdoLingscars->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to 'lingscars' database successfully\n";

    // Connect to the lingscars_admin database
    $dsnLingscarsAdmin = 'mysql:host=127.0.0.1;dbname=lingscars_admin';
    $pdoLingscarsAdmin = new PDO($dsnLingscarsAdmin, $username, $password);
    $pdoLingscarsAdmin->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to 'lingscars_admin' database successfully\n";

    // SQL statements to create tables in lingscars
    $sqlMember = "
    CREATE TABLE IF NOT EXISTS Member (
        memberID INT AUTO_INCREMENT NOT NULL,
        emailMember VARCHAR(50) NOT NULL,
        passMember VARCHAR(50) NOT NULL,
        fullname VARCHAR(100) NOT NULL,
        PRIMARY KEY (memberID)
    )";

    $sqlOrders = "
    CREATE TABLE IF NOT EXISTS Orders (
        quoteID INT AUTO_INCREMENT NOT NULL,
        memberID INT AUTO_INCREMENT NOT NULL,
        firstName VARCHAR(200) NOT NULL,
        lastName VARCHAR(200) NOT NULL,
        address VARCHAR(150) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        brand VARCHAR(150) NOT NULL,
        model VARCHAR(150) NOT NULL,
        spec VARCHAR(200) NOT NULL,
        price VARCHAR(200) NOT NULL,
        arrivaldate DATE NOT NULL,
        orderDate DATE NOT NULL,
        orderTime TIME NOT NULL,
        PRIMARY KEY (quoteID),
        FOREIGN KEY (memberID) REFERENCES Member(memberID)
    )";

    // Execute the SQL statements to create tables in lingscars
    $pdoLingscars->exec($sqlMember);
    $pdoLingscars->exec($sqlOrders);
    echo "Tables 'Member' and 'Orders' created successfully in 'lingscars' database\n";

    // SQL statements to create tables in lingscars_admin
    $sqlAdministrator = "
    CREATE TABLE IF NOT EXISTS Administrator (
        adminID INT AUTO_INCREMENT NOT NULL,
        emailAdmin VARCHAR(50) NOT NULL,
        passAdmin VARCHAR(50) NOT NULL,
        PRIMARY KEY (adminID)
    )";

    $sqlVehicle = "
    CREATE TABLE IF NOT EXISTS Vehicle (
        vehicleID INT AUTO_INCREMENT PRIMARY KEY,
        adminID INT NOT NULL,
        vehicleType VARCHAR(50) NOT NULL,
        vehicleName VARCHAR(100) NOT NULL,
        vehicleBrand VARCHAR(100) NOT NULL,
        vehicleModel VARCHAR(100) NOT NULL,
        vehicleSpec VARCHAR(255) NOT NULL,
        vehiclePrice VARCHAR(50) NOT NULL,
        FOREIGN KEY (adminID) REFERENCES Administrator(adminID)
    )";

    // Execute the SQL statements to create tables in lingscars_admin
    $pdoLingscarsAdmin->exec($sqlAdministrator);
    $pdoLingscarsAdmin->exec($sqlVehicle);
    echo "Tables 'Administrator' and 'Vehicle' created successfully in 'lingscars_admin' database\n";

    // Insert administrators into lingscars_admin
    $administrators = [
        ['emailAdmin' => 'lingscars@admin.com', 'passAdmin' => 'lingling'],
        ['emailAdmin' => 'lingscars2@admin.com', 'passAdmin' => 'lingling2'],
        ['emailAdmin' => 'lingscars3@admin.com', 'passAdmin' => 'lings']
    ];

    $sqlInsert = "INSERT INTO Administrator (emailAdmin, passAdmin) VALUES (:emailAdmin, :passAdmin)";
    $stmt = $pdoLingscarsAdmin->prepare($sqlInsert);

    foreach ($administrators as $admin) {
        $stmt->bindParam(':emailAdmin', $admin['emailAdmin']);
        $stmt->bindParam(':passAdmin', $admin['passAdmin']);

        if ($stmt->execute()) {
            echo "New administrator added successfully: {$admin['emailAdmin']}\n";
        } else {
            echo "Failed to add new administrator: {$admin['emailAdmin']}\n";
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
