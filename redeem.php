<?php
require_once 'functions.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$points = getUserPoints($userId);
$products = getProducts();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    if (redeemProduct($userId, $productId)) {
        $message = "Product redeemed successfully!";
        $points = getUserPoints($userId); // Update points after redemption
    } else {
        $message = "Redemption failed. Not enough points or product unavailable.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Points - Spin to Win</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="redeem-page">
    <a href="logout.php" class="logout-button">Logout</a>
    <div class="redeem-container">
        <h1>Redeem Points</h1>
        <p>Your Points: <span id="points"><?php echo $points; ?></span></p>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <h2>Available Products</h2>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="images\gozoop1.png" alt="<?php echo $product['name']; ?>">
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Points Required: <?php echo $product['points_required']; ?></p>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" <?php echo $points >= $product['points_required'] ? '' : 'disabled'; ?>>REDEEM</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="game.php" class="back-link">Back to Game</a>
    </div>
</body>
</html>