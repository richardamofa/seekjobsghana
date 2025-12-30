<?php
$servername = "localhost"; 
$dbname = "seek_jobs_db"; 
$username = "root"; 
$password = ""; 

try {
    // ✅ Correct DB connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            echo "Login successful! Welcome, " . htmlspecialchars($user['fullname']);
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header("Location: welcome.php");
            exit();
        } else {
            echo "Invalid email or password!";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
