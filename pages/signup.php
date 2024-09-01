<?php 
session_start(); 
require 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $confirm_password = $_POST['confirm-password'];
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: signup.php");
        exit();
    }

    // Handle user image upload
    if (!isset($_FILES['userImage']) || $_FILES['userImage']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "No image uploaded or there was an upload error.";
        header("Location: signup.php");
        exit();
    }

    $userImage = $_FILES['userImage']['name'];
    $target_dir = "../uploads/userImages/";
    $target_file = $target_dir . basename($userImage);

    // Upload the file
    if (!move_uploaded_file($_FILES["userImage"]["tmp_name"], $target_file)) {
        $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        header("Location: signup.php");
        exit();
    }

    // Insert user data into the database (storing only the image file name)
    $sql = "INSERT INTO users (username, password, email, userImage) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        $_SESSION['error'] = 'Prepare() failed: ' . htmlspecialchars($conn->error);
        header("Location: signup.php");
        exit();
    }

    // Bind the parameters (using just the image file name)
    $stmt->bind_param("ssss", $username, $password, $email, $userImage);

    // Execute the statement
    if ($stmt->execute()) {
        
        header("Location: ../index.php"); 
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: signup.php");
        exit();
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
    <title>Sign up</title>
    <link rel="icon" href="../assets/secondary_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="../css/signup.css" rel="stylesheet">
  </head>
  <body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-12 col-md-6">
            <div class="card p-4 card-background signup-text">
                <img src="../assets/logo.png" class="logo-image mx-auto">
                <form method="POST" action="signup.php" id="signUpForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="userImage" class="form-label">User Image</label>
                        <input type="file" class="form-control" name="userImage" id="userImage">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 signup-button">Sign Up</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="../index.php" class="signup-text">Sign In</a></p>
            </div>
        </div>
    </div>
  </body>
</html>
