<?php
// Database connection parameters for the first database
$servername1 = "127.0.0.1";
$username1 = "root";
$password1 = "";
$dbname1 = "lingscars";

// Database connection parameters for the second database
$servername2 = "127.0.0.1";
$username2 = "root";
$password2 = "";
$dbname2 = "lingscars_admin";

// Create connection for the first database
$conn1 = new mysqli($servername1, $username1, $password1, $dbname1);

// Check connection for the first database
if ($conn1->connect_error) {
    die("Connection to database lingscars failed: " . $conn1->connect_error);
}

// Create connection for the second database
$conn2 = new mysqli($servername2, $username2, $password2, $dbname2);

// Check connection for the second database
if ($conn2->connect_error) {
    die("Connection to lingscars_admin failed: " . $conn2->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data with error checking
    $adminID = isset($_POST['adminID']) ? $_POST['adminID'] : null;
    $emailMember = isset($_POST['emailMember']) ? $_POST['emailMember'] : null;
    $passMember = isset($_POST['passMember']) ? $_POST['passMember'] : null;

    // Validate form data (add more validation as needed)
    if (empty($adminID) || empty($emailMember) || empty($passMember)) {
        die("Please fill in all required fields.");
    }

    // Hash the password
    $hashedPass = password_hash($passMember, PASSWORD_BCRYPT);

    // Check if admin ID exists in the second database
    $adminCheckSql = "SELECT * FROM Administrator WHERE adminID = ?";
    $stmt = $conn2->prepare($adminCheckSql);
    $stmt->bind_param("i", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Admin ID exists, proceed with adding the member to the first database
        $insertSql = "INSERT INTO Member (emailMember, passMember) 
                      VALUES (?, ?)";
        $stmt = $conn1->prepare($insertSql);
        $stmt->bind_param("ss", $emailMember, $hashedPass);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Member added successfully!');
                    window.location.href = 'managemembers.html';
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

// Close connections
$conn1->close();
$conn2->close();
?>
