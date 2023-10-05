<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/db_connection.php";

$sql = "SELECT * FROM utenti
        WHERE reset_token = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token non trovato");
}

if (strtotime($user["reset_token_expiration"]) <= time()) {
    die ("Il token è scaduto");
}

// echo "il token è valido e non è scaduto";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
</head>
<body>
    <h1>Reset Password</h1>

    <form action="process-reset-password.php" method="post">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New Password</label>
        <input type="password" id="password" name="password"><br>

        <label for="password_confirmation">Repeat Password</label>
        <input type="password" id="password_confirmation" 
               name="password_confirmation"><br>

        <button>Invia</button>
    </form>
</body>
</html>