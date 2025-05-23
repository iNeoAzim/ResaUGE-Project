<?php
$password = 'Admin123!';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: Admin123!<br>";
echo "Generated Hash: " . $hash . "<br>";
echo "Verification test: " . (password_verify($password, $hash) ? "Success" : "Failed") . "<br>";

// Test avec le hash stocké dans la base de données
$stored_hash = '$2y$10$8zUUpxqyXGF0H0uGMvs9.eKHrPGhXOyh0UF4jx1NZhF9J4kBg0Ife';
echo "Verification with stored hash: " . (password_verify($password, $stored_hash) ? "Success" : "Failed") . "<br>";
?> 