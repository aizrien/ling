<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$adminDbname = "lingscars_admin";

// Create connection to lingscars_admin database
$adminConn = new mysqli($servername, $username, $password, $adminDbname);

// Check admin connection
if ($adminConn->connect_error) {
    die("Admin connection failed: " . $adminConn->connect_error);
}

// Fetch all admin records
$adminSql = "SELECT * FROM administrator";
$adminResult = $adminConn->query($adminSql);

if ($adminResult->num_rows > 0) {
    while ($adminRow = $adminResult->fetch_assoc()) {
        $email = $adminRow['emailadmin'];
        $plainPassword = $adminRow['passadmin']; // Assuming adminpass is currently in plain text
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Update the adminpass field with the hashed password
        $updateSql = "UPDATE administrator SET passadmin='$hashedPassword' WHERE emailadmin='$email'";
        if ($adminConn->query($updateSql) === TRUE) {
            echo "Password for $email updated successfully.<br>";
        } else {
            echo "Error updating password for $email: " . $adminConn->error . "<br>";
        }
    }
} else {
    echo "No admin records found.";
}

// Close the connection
$adminConn->close();
?>
