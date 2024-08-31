<?php 
session_start();
require 'config.php'; // Adjust the path as needed

// Fetch categories
$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);

// Fetch products and related questions
$sql = "SELECT p.ProductID, p.ProductName, p.StartingPrice, p.ProductImage, p.Description, q.questionid, q.heading, q.question_text, u.username, u.userImage
        FROM product p
        JOIN question q ON p.QuestionID = q.questionid
        JOIN users u ON q.userid = u.userid
        WHERE p.categoriesid = ?
        ORDER BY p.CreatedAt DESC
        LIMIT 5";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Check if a category is selected and bind the category ID
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 1; // Default to category ID 1
$stmt->bind_param("i", $category_id);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/main.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/318f00e1f3.js" crossorigin="anonymous"></script>
    <style>
      .card {
        position: relative;
      }
      .view-question-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
      }
      .category-image {
    width: 150px !important; /* Adjust the width as needed */
    height: 150px !important; /* Adjust the height as needed */
    object-fit: cover; /* Ensures the image covers the area without distortion */
}

    </style>
  </head>
  <body>
    <?php include '../components/navbar.php'; ?>
    
    <div class="container mt-5 ">
        <div class="text-center mb-4">
            <h3>Product Categories</h3>
        </div>
        <div class="row mx-auto ">
            <?php while ($category = $categories_result->fetch_assoc()): ?>
                <div class="col-2">
                    <a href="?category_id=<?php echo $category['categoriesid']; ?>">
                        <img src="../uploads/categorieImage/<?php echo htmlspecialchars($category['categorie_image']); ?>" class="img-fluid image-categories" alt="<?php echo htmlspecialchars($category['categorie_name']); ?>">
                        <div class="text-center mb-4">
                            <p><?php echo htmlspecialchars($category['categorie_name']); ?></p>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div class="text-center mb-4">
            <h1>Product Feed</h1>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card col-8 mx-auto mb-4 post-background">
                    <div class="card-body">
                        <div class="view-question-btn">
                            <a href="singleproductpage.php?id=<?php echo htmlspecialchars($row['ProductID']); ?>" class="btn btn-primary">View Product</a>
                        </div>
                        <div class="row">
                            <div class="col-1">
                                <img src="../uploads/userImages/<?php echo htmlspecialchars($row['userImage']); ?>" alt="User Image" class="rounded-circle" style="width: 50px; height: 50px;">
                            </div>
                            <div class="col-2">
                                <p><?php echo htmlspecialchars($row['username']); ?></p>
                            </div>
                        </div>
                        <h5 class="card-title"><?php echo htmlspecialchars($row['heading']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['question_text']); ?></p>
                        <h5 class="card-title"><?php echo htmlspecialchars($row['ProductName']); ?></h5>
                        <p class="card-text">Price: R<?php echo htmlspecialchars($row['StartingPrice']); ?></p>
                        <?php if (!empty($row['ProductImage'])): ?>
                            <img class="img-fluid" src="../uploads/<?php echo htmlspecialchars($row['ProductImage']); ?>" alt="Product Image">
                        <?php endif; ?>
                        <p class="card-text"><?php echo htmlspecialchars($row['Description']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>

    </div>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
