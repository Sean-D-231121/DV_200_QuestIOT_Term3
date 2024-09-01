<?php 
session_start(); // Start the session
require 'config.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php"); 
    exit(); 
}

// Retrieve the ProductID from the URL
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialize variables
$error = '';
$success = '';

// Fetch product details if the ProductID is valid
if ($productID > 0) {
    $sql = "SELECT p.ProductName, p.StartingPrice, p.ProductImage, p.Description, p.QuestionID, u.username, u.userImage 
            FROM product p 
            JOIN users u ON p.userid = u.userid
            WHERE p.ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $error = "Product not found.";
    }
    $stmt->close();
} else {
    $error = "Invalid product ID.";
}

// Handle the offer submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $offerAmount = isset($_POST['offerAmount']) ? floatval($_POST['offerAmount']) : 0;
    $userID = $_SESSION['userid']; // Assuming you have the user ID stored in the session

    // Fetch the highest current offer
    $sql = "SELECT MAX(OfferAmount) AS HighestOffer FROM offer WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $highestOfferResult = $stmt->get_result();
    $highestOffer = $highestOfferResult->fetch_assoc()['HighestOffer'];
    $stmt->close();

    // Determine the minimum acceptable offer
    $minAcceptableOffer = max($row['StartingPrice'], $highestOffer);

    if ($offerAmount > $minAcceptableOffer) {
        // Insert the new offer
        $sql = "INSERT INTO offer (ProductID, userid, OfferAmount, CreatedAt) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iid", $productID, $userID, $offerAmount);
        if ($stmt->execute()) {
            $success = "Offer placed successfully!";
        } else {
            $error = "Failed to place offer.";
        }
        $stmt->close();
    } else {
        $error = "Offer must be higher than the current highest offer.";
    }
}

// Fetch all offers for the product
$sql = "SELECT o.OfferAmount, u.username, u.userImage, o.CreatedAt 
        FROM offer o 
        JOIN users u ON o.userid = u.userid
        WHERE o.ProductID = ? ORDER BY o.CreatedAt DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$offers_result = $stmt->get_result();

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Detail</title>
    <link rel="icon" href="../assets/secondary_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/main.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/318f00e1f3.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include '../components/navbar.php'; ?>

    <div class="container mt-3">
        <a href="productspage.php" class="btn btn-outline-sucess">Go back</a>
    </div>

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Product Detail</h1>
        </div>

        <?php if ($error): ?>
            <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($success): ?>
            <p class="text-success text-center"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <?php if (isset($row)): ?>
            <div class="card col-8 mx-auto mb-4 post-background">
                <div class="card-body">
                    <div class="row">
                        <div class="col-1">
                            <img src="../uploads/userImages/<?php echo htmlspecialchars($row['userImage']); ?>" alt="User Image" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                        </div>
                        <div class="col-2">
                            <p><?php echo htmlspecialchars($row['username']); ?></p>
                        </div>
                    </div>
                    <h5 class="card-title"><?php echo htmlspecialchars($row['ProductName']); ?></h5>
                    <p class="card-text">Starting Price: R<?php echo htmlspecialchars($row['StartingPrice']); ?></p>
                    <?php if (!empty($row['ProductImage'])): ?>
                        <img class="img-fluid" src="../uploads/<?php echo htmlspecialchars($row['ProductImage']); ?>" alt="Product Image">
                    <?php endif; ?>
                    <p class="card-text"><?php echo htmlspecialchars($row['Description']); ?></p>

                   
                    <?php if (!empty($row['QuestionID'])): ?>
                        <a href="questionpostpage.php?id=<?php echo htmlspecialchars($row['QuestionID']); ?>" class="btn btn-info">View Related Question</a>
                    <?php endif; ?>

                   
                    <form method="post" class="mt-4">
                        <div class="mb-3">
                            <label for="offerAmount" class="form-label">Your Offer</label>
                            <input type="number" step="0.01" min="<?php echo htmlspecialchars($minAcceptableOffer); ?>" class="form-control" id="offerAmount" name="offerAmount" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Place Offer</button>
                    </form>
                </div>
            </div>

            <div class="text-center mb-4">
                <h1>Offers</h1>
            </div>

            <?php if ($offers_result->num_rows > 0): ?>
                <?php while ($offer = $offers_result->fetch_assoc()): ?>
                    <div class="card mt-4 post-background">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-1">
                                    <img src="../uploads/userImages/<?php echo htmlspecialchars($offer['userImage']); ?>" alt="User Image" class="img-thumbnail rounded-circle" style="width: 40px; height: 40px;">
                                </div>
                                <div class="col-2">
                                    <p><?php echo htmlspecialchars($offer['username']); ?></p>
                                </div>
                            </div>
                            <p><?php echo htmlspecialchars($offer['username']); ?> offered:</p>
                            <p>R<?php echo htmlspecialchars($offer['OfferAmount']); ?></p>
                            <p><small class="text-muted">Offered on <?php echo htmlspecialchars($offer['CreatedAt']); ?></small></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No offers yet.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
