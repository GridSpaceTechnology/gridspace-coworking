<?php

// Simple test - bypass all middleware and custom logic

use App\Models\User;
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        echo "✅ LOGIN SUCCESS! Redirecting...";
        header("Refresh: 2; URL=/dashboard");
        exit;
    } else {
        $error = "❌ Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Test Login</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; }
        input { display: block; width: 100%; margin: 10px 0; padding: 10px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Simple Test Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" value="admin@gridspace.com" required>
        <input type="password" name="password" placeholder="Password" value="password" required>
        <button type="submit">Login</button>
    </form>
    <p><small>This bypasses all custom logic for testing</small></p>
</body>
</html>
