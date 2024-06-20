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
    die("Connection to lingscars_Admin failed: " . $conn2->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $adminID = $_POST['adminID']; 
    $memberID = $_POST['memberID'];

    // Check if admin ID exists in the second database
    $adminCheckSql = "SELECT * FROM Administrator WHERE adminID = ?";
    $stmt2 = $conn2->prepare($adminCheckSql);
    $stmt2->bind_param("i", $adminID);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
        // Prepare delete statement for the first database
        $sql = "DELETE FROM Member WHERE memberID = ?";
        $stmt1 = $conn1->prepare($sql);
        $stmt1->bind_param("i", $memberID);

        if ($stmt1->execute()) {
            echo "<script>
                    alert('You successfully deleted a registered member!');
                    window.location.href = 'managemembers.html';
                  </script>";
        } else {
            echo "<script>
                    alert('An error occurred while deleting the member. Please try again later.');
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

// Close connections
$conn1->close();
$conn2->close();
?>
