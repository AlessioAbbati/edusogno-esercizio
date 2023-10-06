<?php
session_start();

include "header.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/style.css?v=<?php echo time(); ?>">
    <title>Forgot password</title>
</head>
<body>
    <h1>Reset Password</h1>

    <form action="reset_pass_logic.php" method="post">
        <label for="email">email</label>
        <input type="email" name="email" id="email">

        <button>Invia</button>
    </form>
</body>
</html>