<?php
session_start();
include('config/db.php');

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $errors[] = "Both fields are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $errors[] = "Invalid username or password.";
                }
            } else {
                $errors[] = "Invalid username or password.";
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            error_log("Login query failed: " . $e->getMessage());
            $errors[] = "An error occurred. Please try again later.";
        }
    }
}
?>

<?php include('includes/header.php'); ?>
<h2>Login</h2>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="error">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" class="needs-validation" novalidate>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        <div class="invalid-feedback">Please enter a username.</div>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="invalid-feedback">Please enter a password.</div>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Login</button>
</form>

