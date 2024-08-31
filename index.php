<?php 
session_start(); // Start the session
require 'pages/config.php'; // Include the config file for database connection

// Check if the form has been submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // SQL query to find the user by username 
    $sql = "SELECT * FROM users WHERE username = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind the username to the SQL statement
    $stmt->bind_param("s", $username); // "s" means a string parameter
    
    // Execute the SQL statement
    $stmt->execute();

    // Store the result in the 'result' variable
    $result = $stmt->get_result();

    // Check if user exists
    if($result->num_rows > 0){
        // Fetch user data
        $user = $result->fetch_assoc();

        // If the password of the form input = stored password of user found
        if($password === $user['password']){

            // Store user information in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['userid'] = $user['userid']; // Store userid in session
            // Redirect to home page
            header("Location: pages/home.php");
            exit(); // Terminate the script to ensure redirection
        } else {
            echo "Invalid username or password"; // (Technically just: password wrong)
        }
    } else {
        echo "Invalid username or password"; // (Technically just: username not found)
    }
    
    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close(); 
}
?>

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="./css/main.css" rel="stylesheet">
    <link href="./css/signup.css" rel="stylesheet">
  </head>
  <body>
     <div class="container d-flex justify-content-center align-items-center vh-100 signup-text">
        <div class="col-12 col-md-6">
            <div class="card p-4 card-background signup-text">
                <img src="./assets/logo.png" class="logo-image mx-auto"  />
                <h4 class="text-center">Welcome back!</h4>
                <form method="POST" action="index.php" >
                    <div class="mb-3">
                        <label for="username" class="form-label ">username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="JohnDoe123" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 signup-button ">Sign In</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a class="signup-text" href="./pages/signup.php">Sign Up</a></p>
            </div>
        </div>
    </div>
    
  </body>
</html>