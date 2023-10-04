<?php
session_start();
include "db_connection.php";

if (isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email']) && isset($_POST['password'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $nome = validate($_POST['nome']);
    $cognome = validate($_POST['cognome']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    
    

    if (empty($nome)) {
        header("location: register.php?error=nome is required");
        exit();
    }else if (empty($cognome)) {
        header("location: register.php?error=cognome is required");
        exit();
    }
    else if (empty($email)) {
        header("location: register.php?error=email is required");
        exit();
    }
    else if (empty($password)) {
        header("location: register.php?error=password is required");
        exit();
    }else {
        $sql_new_user = "INSERT INTO utenti (nome, cognome, email, password)
            VALUES ('$nome', '$cognome', '$email', '$password')";

        if (mysqli_query($conn, $sql_new_user)) {
            $_SESSION['id'] = mysqli_insert_id($conn);
            $_SESSION['nome'] = $nome;
            $_SESSION['cognome'] = $cognome;
            $_SESSION['email'] = $email;
            header("Location: home.php");
            
        }else {
            header("Location: register.php?error=Invalid input insert");
            exit();
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/style.css">
    <title>Register</title>
</head>

<body>
    <form action="" method="post">
        <h1>Register</h1>

        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <label>Nome</label>
        <input type="text" name="nome" placeholder="nome"><br>

        <label>Cognome</label>
        <input type="text" name="cognome" placeholder="cognome"><br>

        <label>Email</label>
        <input type="email" name="email" placeholder="email"><br>

        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit">Registrati</button>

        <p>Hai gia un account? <a href="login.php">Accedi</a></p>
        

    </form>

</body>

</html>