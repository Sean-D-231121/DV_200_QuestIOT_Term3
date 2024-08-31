<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get question data
    $heading = $_POST['heading'];
    $question_text = $_POST['question_text'];
    $userid = $_SESSION['userid'];
    $categoriesid = $_POST['categoriesid'];

    // Insert question into the database
    $sql = "INSERT INTO question (heading, question_text, userid, categoriesid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $heading, $question_text, $userid, $categoriesid);

    if ($stmt->execute()) {

        // Get the last inserted question ID
        $question_id = $conn->insert_id;

        // Check if product data is provided
        if (!empty($_POST['product_name']) && !empty($_POST['starting_price'])) {

            // Get product data
            $product_name = $_POST['product_name'];
            $starting_price = $_POST['starting_price'];
            $product_image = !empty($_FILES['product_image']['name']) ? $_FILES['product_image']['name'] : null;
            $description = !empty($_POST['description']) ? $_POST['description'] : null;

            // Handle file upload if image is provided
            if ($product_image) {
                $target_dir = "../uploads/"; // Adjusted path to the uploads directory
                $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
                move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
            }

            // Insert product into the database with userid
            $sql_product = "INSERT INTO product (ProductName, StartingPrice, ProductImage, Description, CreatedAt, QuestionID, CategoriesID, userid) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
            $stmt_product = $conn->prepare($sql_product);
            $stmt_product->bind_param("sdssiii", $product_name, $starting_price, $product_image, $description, $question_id, $categoriesid, $userid);

          
            $stmt_product->close();
        }

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="../css/main.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/318f00e1f3.js" crossorigin="anonymous"></script>
    <style>
        .container {
            max-width: 600px; /* Limit the maximum width */
            padding-top: 20px; /* Add some space from the top */
        }
        .card {
            border-radius: 8px; /* Rounded corners */
        }
        .post-background {
            background-color: #f8f9fa; /* Light background */
        }
        .product-section {
            display: none; /* Hide the product section by default */
        }
    </style>
    <script>
        function toggleProductSection() {
            var productSection = document.getElementById('productSection');
            if (productSection.style.display === 'none' || productSection.style.display === '') {
                productSection.style.display = 'block';
            } else {
                productSection.style.display = 'none';
            }
        }
    </script>
  </head>
  <body>
    <?php include '../components/navbar.php'; ?>

    <div class="container d-flex justify-content-center">
        <div class="col-12">
            <div class="card p-4 post-background">
                <h2 class="text-center">Create a Post</h2>
                <form method="POST" action="createpostpage.php" id="postForm" enctype="multipart/form-data">
                    <!-- Question Fields -->
                    <div class="mb-3">
                        <label for="heading" class="form-label">Headline</label>
                        <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter headline" required>
                    </div>
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Body</label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="4" placeholder="Enter body text"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categoriesid" class="form-label">Categories</label>
                        <select class="form-select" id="categoriesid" name="categoriesid" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="1">Computers</option>
                            <option value="2">3D design</option>
                            <option value="3">Education</option>
                            <option value="4">Gaming</option>
                            <option value="5">Software</option>
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="addProduct" onclick="toggleProductSection()">
                        <label class="form-check-label" for="addProduct">
                            Add Product
                        </label>
                    </div>

                    <div id="productSection" class="product-section">
                        <h3 class="text-center">Product Details</h3>
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name">
                        </div>
                        <div class="mb-3">
                            <label for="starting_price" class="form-label">Starting Price</label>
                            <input type="number" step="0.01" class="form-control" id="starting_price" name="starting_price" placeholder="Enter starting price">
                        </div>
                        <div class="mb-3">
                            <label for="product_image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="product_image" name="product_image">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3 row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary w-100">Post</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </body>
</html>
