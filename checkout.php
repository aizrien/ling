<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['emailmember']) || empty($_SESSION['emailmember'])) {
    header("Location: login1.php");
    exit;
}

// Include database configuration
require_once('config.php');

// Database connection
try {
    $conn = new PDO($dsnLingscars, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize variables with form data
$emailmember = $_SESSION['emailmember'];
$firstName = htmlspecialchars($_POST['firstName']);
$lastName = htmlspecialchars($_POST['lastName']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$address = htmlspecialchars($_POST['address']);

// Insert data into cart table
$sql = "INSERT INTO cart (emailmember, firstName, lastName, email, phone, address)
        VALUES (:emailmember, :firstName, :lastName, :email, :phone, :address)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':emailmember', $emailmember, PDO::PARAM_STR);
$stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
$stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
$stmt->bindParam(':address', $address, PDO::PARAM_STR);

try {
    $stmt->execute();
    // Redirect to payment page or display success message
    header("Location: payment.html"); // Redirect to payment page
    exit;
} catch (PDOException $e) {
    die("Error inserting data into cart: " . $e->getMessage());
}

// Close database connection
$conn = null;
?>
