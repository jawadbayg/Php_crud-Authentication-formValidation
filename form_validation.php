<?php
require 'db.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $date = $_POST['date'];
    $email = $_POST['email'];
    $skills = isset($_POST['skills']) ? implode(", ", $_POST['skills']) : '';

    $stmt = $conn->prepare("INSERT INTO form_val (user_id, phone, age, date, email, skills) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $user_id, $phone, $age, $date, $email, $skills);

    if ($stmt->execute()) {
        $_SESSION['form_success'] = true;
        header('Location: todo.php');
        echo '<script>console.log("Data added");</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
        <div class="h2">FORM</div >
    <form action="" method="post">
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" name="phone" id="phone" required>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" class="form-control" id="age" name="age" required>
        </div>
        <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" name="date" id="date" required>
        </div>
        <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="skills">Skills:</label><br>
            <input type="checkbox" id="C++" name="skills[]" value="C++">
            <label for="C++">C++</label><br>
            <input type="checkbox" id="OOP" name="skills[]" value="OOP">
            <label for="OOP">OOP</label><br>
            <input type="checkbox" id="DSA" name="skills[]" value="DSA">
            <label for="DSA">DSA</label><br>
            <input type="checkbox" id="GIT" name="skills[]" value="GIT">
            <label for="GIT">Git</label><br>
            <input type="checkbox" id="XAMPP" name="skills[]" value="XAMPP">
            <label for="XAMPP">XAMPP</label><br>
            <small class="text-danger"></small>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>    

    <script>
        document.getElementById('phone').addEventListener('input', function (e) {
            var value = e.target.value;
            e.target.value = value.replace(/[^0-9]/g, '');
        });
    </script>

</body>
</html>
