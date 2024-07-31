<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'];
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $task);

    if ($stmt->execute() === TRUE) {
        
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$form_success = isset($_SESSION['form_success']) ? $_SESSION['form_success'] : false;

unset($_SESSION['form_success']);


$sql = "SELECT * FROM tasks WHERE user_id='$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">To-Do List</h2>

        <form action="todo.php" method="POST" class="mb-4">
            <div class="form-group">
                <input type="text" class="form-control" name="task" placeholder="Enter new task" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>

        <ul class="list-group">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li class='list-group-item'>" . htmlspecialchars($row['task']) . " <span class='text-muted float-right'>" . $row['created_at'] . "</span></li>";
                }
            } else {
                echo "<li class='list-group-item'>No tasks found.</li>";
            }
            ?>
        </ul>
        <a href="form_validation.php" class="btn btn-primary mt-4">Form Validation</a>
        <a href="form_data.php" class="btn btn-primary mt-4">Form Data</a>

        <a class="btn btn-danger mt-4" id="logout-button">Logout</a>
    </div>
    <script>
            // Show SweetAlert if form submission was successful
            <?php if ($form_success): ?>
                Swal.fire({
                    title: 'Success!',
                    text: 'Your data has been added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        </script>
        <script>
    document.getElementById('logout-button').addEventListener('click', function() {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor:"#d33" ,
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        });
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
