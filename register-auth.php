<?php
session_start();
include('./db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $cpass = isset($_POST['cpass']) ? $_POST['cpass'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : 2; // Default to 'staff' if not set
    $branch_id = isset($_POST['branch_id']) ? $_POST['branch_id'] : 0;

    // Password validation
    if ($password && $password !== $cpass) {
        echo "<script>alert('Passwords do not match.'); window.location = 'index.php?page=sign_up';</script>";
        exit;
    }

    // Encrypt password
    $hashed_password = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

    // Check if it's an update or insert
    if ($id) {
        // Update user
        if ($password) {
            $sql = "UPDATE users SET firstname=?, lastname=?, email=?, password=?, type=?, branch_id=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                exit;
            }
            $stmt->bind_param('ssssiis', $firstname, $lastname, $email, $hashed_password, $type, $branch_id, $id);
        } else {
            $sql = "UPDATE users SET firstname=?, lastname=?, email=?, type=?, branch_id=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                exit;
            }
            $stmt->bind_param('sssisi', $firstname, $lastname, $email, $type, $branch_id, $id);
        }
    } else {
        // Insert new user
        $sql = "INSERT INTO users (firstname, lastname, email, password, type, branch_id, date_created) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            exit;
        }
        $stmt->bind_param('ssssis', $firstname, $lastname, $email, $hashed_password, $type, $branch_id);
    }

    // Execute query
    if ($stmt->execute()) {
        // User saved successfully, redirect to the homepage
        echo "<script>alert('User saved successfully.'); window.location = 'index.php?page=home';</script>";
    } else {
        // Error occurred, display error message and stay on the signup page
        echo "<script>alert('Error: " . $stmt->error . "'); window.location = 'index.php?page=sign_up';</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
