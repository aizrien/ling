<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['emailmember']) || empty($_SESSION['emailmember'])) {
    header("Location: login1.php");
    exit;
}

require_once('config.php');

// Database connection
try {
    $conn = new PDO($dsnLingscars, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user data based on email from session
$emailmember = $_SESSION['emailmember'];

$sql = "SELECT emailmember, fullname FROM Member WHERE emailmember = :emailmember";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':emailmember', $emailmember, PDO::PARAM_STR);

if ($stmt->execute()) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $email = $result['emailmember'];
        $fullName = $result['fullname'];
    } else {
        die("No user found with the given email.");
    }
} else {
    die("Execute failed: " . $stmt->errorInfo()[2]);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $newFullName = htmlspecialchars($_POST['fullname']);
    $newEmail = htmlspecialchars($_POST['email']);

    // Update database
    $updateSql = "UPDATE Member SET fullname = :fullname, emailmember = :email WHERE emailmember = :current_email";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':fullname', $newFullName, PDO::PARAM_STR);
    $updateStmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
    $updateStmt->bindParam(':current_email', $emailmember, PDO::PARAM_STR);

    try {
        $updateStmt->execute();
        $fullName = $newFullName; // Update displayed name immediately
        $email = $newEmail; // Update displayed email immediately
        // Success message or redirect to profile page
        // Example:
        // header("Location: profile.php?success=1");
        // exit;
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}

// Close database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Profile</title>
    <link rel="stylesheet" href="keyinfo.css"> <!-- Link to your CSS file -->
    <style>
        /* Add the necessary CSS here */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #8B0000;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px;
        }

        nav a:hover {
            background-color: #575757;
            border-radius: 4px;
        }

        nav .logo {
            width: 150px;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .profile-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button-container {
            text-align: right;
        }

        .edit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #45a049;
        }
        .edit-button {
            background-color: maroon;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: darkred;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #8B0000;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <img src="logo1.png" alt="Logo" class="logo">
        <a href="dashboardmember.html">Dashboard</a>
        <a href="vehiclespecmember.html">Vehicle Specifications</a>
        <a href="keyinfomember.html">Key Information</a>
        <a href="contactmember.html">Contact Us</a>
        <a href="quote.html">Order Now!</a>
        <a href="notification.html">Notifications</a>
        <a href="profile.php">Account Profile</a>
        <a href="inbox.html">Inbox</a>
        <a href="index.html" onclick="logout()">Log Out</a>
    </nav>
    
    <!-- Main content -->
     <main>
        <div class="profile-container">
            <h1>Account Profile</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="showAlert(event)">
                <div class="profile-info">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="profile-info">
                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullname" value="<?php echo htmlspecialchars($fullName); ?>">
                </div>
                <div class="button-container">
                    <button type="submit" class="edit-button">Save Changes</button>
                </div>
            </form>
        </div>
    </main>
    
    <!-- Footer -->
    <footer>
        &copy; 2024 Member Dashboard. All rights reserved.
    </footer>
    <script>
        function showAlert(event) {
            alert("Changes saved");
        }
    </script>
</body>
</html>
