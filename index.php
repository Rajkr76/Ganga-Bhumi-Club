<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ganga_bhumi_club";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if action is set
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'signup') {
            // Validate input
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = $password = isset($_POST['password']) ? trim($_POST['password']) : '';            ;

            if (empty($name) || empty($email) || empty($_POST['password'])) {
                echo "<script>alert('Please fill in all fields.');</script>";
            } else {
                // Check if email already exists
                $checkEmailQuery = "SELECT * FROM ganga_loginpage WHERE email = '$email'";

                $result = $conn->query($checkEmailQuery);

                if ($result->num_rows > 0) {
                    echo "<script>alert('Email already exists. Please use a different email.');</script>";
                } else {
                    // Insert user into the database
                    $query = "INSERT INTO `ganga_loginpage` (`name`, `email`, `password`, `dt`) VALUES ('$name', '$email', '$password', current_timestamp());";


                    if ($conn->query($query) === TRUE) {
                        echo "<script>alert('Account created successfully! Please login.');</script>";
                    } else {
                        echo "<script>alert('Error: Could not create account.');</script>";
                    }
                }
            }
        } elseif ($_POST['action'] === 'login') {
            // Validate input
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($email) || empty($password)) {
                echo "<script>alert('Please fill in all fields.');</script>";
            } else {
                // Check if the user exists
                $query = "SELECT * FROM ganga_loginpage WHERE email = '$email'";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    // Verify password
                    if ($password === $user['password']) {
                        echo "<script>alert('Login successful! Welcome, {$user['name']}.');</script>";
                    } else {
                        echo "<script>alert('Invalid password. Please try again.');</script>";
                    }
                } else {
                    echo "<script>alert('No account found with this email. Please sign up.');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('No action specified.');</script>";
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup Page</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playwrite+IE+Guides&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-container login-container active" id="loginForm">
        <h1> <big> login form </big></h1>
        <form method="POST" action="index.php">
      
    <input type="hidden" name="action" value="login">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <p class="toggle-link" id="showSignup">Create a new account</p>
</form>

        </div>

        <!-- Signup Form -->
        <div class="form-container sign-up-container hidden" id="signupForm">
        <h1> <big> Sign-up form </big></h1>
        <form method="POST" action="index.php">
    <input type="hidden" name="action" value="signup">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Sign Up</button>
    <p class="toggle-link" id="showLogin">Already a member? Sign In</p>
</form>

        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');
        const showSignup = document.getElementById('showSignup');
        const showLogin = document.getElementById('showLogin');

        showSignup.addEventListener('click', () => {
            loginForm.classList.add('hidden');
            loginForm.classList.remove('active');
            signupForm.classList.add('active');
            signupForm.classList.remove('hidden');
        });

        showLogin.addEventListener('click', () => {
            signupForm.classList.add('hidden');
            signupForm.classList.remove('active');
            loginForm.classList.add('active');
            loginForm.classList.remove('hidden');
        });

        // Redirect back to login after signing up
        const signupSubmit = document.getElementById('signupSubmit');
        signupSubmit.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent form submission
            alert('Account created! Redirecting to login.');
            signupForm.classList.add('hidden');
            signupForm.classList.remove('active');
            loginForm.classList.add('active');
            loginForm.classList.remove('hidden');
        });
    </script>
</body>
</html>
