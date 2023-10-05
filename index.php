<?php 
include "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/logStyle.css">
    <title>Login</title>
</head>

<body>
    <h1>Hai gia un account?</h1>
    <form action="login.php" method="post">
        

        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <label>Inserisci l'e-mail</label>
        <input type="email" name="email" placeholder="name@example.com"><br>

        <label>Inserisci la password</label>
        <input type="password" name="password" placeholder="Scrivila qui"><br>

        <button type="submit">Login</button>

        <p>Non hai ancora un profilo? <a href="register.php">Registrati</a></p>
    </form>

</body>

</html>