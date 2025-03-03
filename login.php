<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$database = "student_registration";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = $_POST['lName'] ?? "";
    $first_name = $_POST['fName'] ?? "";
    $student_id = $_POST['studentID'] ?? "";

    // Prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM students WHERE last_name = ? AND first_name = ? AND student_id = ?");
    $stmt->bind_param("sss", $last_name, $first_name, $student_id);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, start session
        $user = $result->fetch_assoc();
        $_SESSION['student_id'] = $user['student_id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];

        // Redirect to Dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
