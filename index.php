<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/style.css">
    <title>Login</title>
</head>

<body>
    <form action="login.php" method="post">
        <h1>Login</h1>

        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <label>Email</label>
        <input type="email" name="email" placeholder="email"><br>

        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit">Login</button>

        <p>Non hai ancora un profilo? <a href="register.php">Registrati</a></p>
    </form>

</body>

</html>