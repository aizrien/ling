// cart.php (PHP file)

<?php
session_start();

// Initialize the cart array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if cart is not empty
if (!empty($_SESSION['cart'])) {
    $dsn = 'ysql:host=127.0.0.1;dbname=lingscars';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $cart = $_SESSION['cart'];
        $cartHtml = '';

        foreach ($cart as $item) {
            $stmt = $pdo->prepare("SELECT * FROM Vehicle WHERE vehicleID =?");
            $stmt->execute([$item['item']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $cartHtml.= "<p>Item: ". htmlspecialchars($row['vehicleBrand']). " ". htmlspecialchars($row['vehicleModel']). "</p>";
            $cartHtml.= "<p>Quantity: ". $item['quantity']. "</p>";
            $cartHtml.= "<p>Price: ". $item['price']. "</p>";
        }

        // Render the cart HTML
        include 'cart.html.php';

    } catch (PDOException $e) {
        // Log the error and display a friendly message
        error_log('Database connection failed: '. $e->getMessage());
        echo '<p>Sorry, an error occurred. Please try again later.</p>';
        exit;
    }
} else {
    echo "<p>Cart is empty.</p>";
}

// Add item to cart (e.g. from a form submission or AJAX request)
if (isset($_POST['add_to_cart'])) {
    $item = array(
        'item' => $_POST['item_name'],
        'quantity' => $_POST['item_quantity'],
        'price' => $_POST['item_price']
    );

    // Validate user input
    if (empty($item['item']) || empty($item['quantity']) || empty($item['price'])) {
        echo '<p>Please fill in all fields.</p>';
        exit;
    }

    $_SESSION['cart'][] = $item;
    header('Location: cart.php');
    exit;
}