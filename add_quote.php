<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection details
    $dsn = 'mysql:host=127.0.0.1;dbname=lingscars';
    $username = 'root';
    $password = '';

    try {
        // Create a PDO instance
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve form data
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $phone = htmlspecialchars($_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        $brand = htmlspecialchars($_POST['brand']);
        $model = htmlspecialchars($_POST['model']);
        $spec = htmlspecialchars($_POST['spec']);
        $price = htmlspecialchars($_POST['price']);
        $arrivaldate = htmlspecialchars($_POST['arrivaldate']);
        $memberID = intval($_POST['memberID']); // Convert to integer assuming memberID is submitted from the form

        // Ensure OrderDate and OrderTime are properly set
        $orderDate = date('Y-m-d'); // Current date as YYYY-MM-DD
        $orderTime = date('H:i:s'); // Current time as HH:MM:SS

        // Check if memberID exists in the member table
        $checkMemberStmt = $pdo->prepare("SELECT memberID FROM Member WHERE memberID = :memberID");
        $checkMemberStmt->bindParam(':memberID', $memberID, PDO::PARAM_INT);
        $checkMemberStmt->execute();

        if ($checkMemberStmt->rowCount() == 0) {
            throw new Exception("Invalid memberID: $memberID does not exist in the Member table.");
        }

        // Insert new order record with specified memberID and vehicle details
        $stmt = $pdo->prepare("INSERT INTO Orders (MemberID, FirstName, LastName, Address, Phone, Brand, Model, Spec, Price, ArrivalDate, OrderDate, OrderTime) 
                               VALUES (:memberID, :firstName, :lastName, :address, :phone, :brand, :model, :spec, :price, :arrivaldate, :orderDate, :orderTime)");

        $stmt->bindParam(':memberID', $memberID, PDO::PARAM_INT);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':spec', $spec);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':arrivaldate', $arrivaldate);
        $stmt->bindParam(':orderDate', $orderDate);
        $stmt->bindParam(':orderTime', $orderTime);
        
        $stmt->execute();

        // Redirect to confirmation page after successful insertion
        header("Location: receipt.php?firstName=$firstName&lastName=$lastName&phone=$phone&address=$address&brand=$brand&model=$model&spec=$spec&price=$price&arrivaldate=$arrivaldate");
        exit();

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if request method is not POST
    header("Location: quote.html");
    exit();
}
?>
