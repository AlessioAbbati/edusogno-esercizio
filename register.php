<?php
session_start();
include "db_connection.php";
include "header.php";

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
    <link rel="stylesheet" href="assets/styles/logStyle.css">
    <title>Register</title>
</head>

<body>
    <h1>Crea il tuo account</h1>
    <form action="" method="post">
        

        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <label>Inserisci il nome</label>
        <input type="text" name="nome" placeholder="Mario"><br>

        <label>Inserisci il cognome</label>
        <input type="text" name="cognome" placeholder="Rossi"><br>

        <label>Inserisci l'e-mail</label>
        <input type="email" name="email" placeholder="name@example.com"><br>

        <label>Inserisci la password</label>
        <input type="password" name="password" placeholder="Scrivila qui"><br>

        <button type="submit">Registrati</button>

        <p>Hai gia un account? <a href="login.php">Accedi</a></p>
        

    </form>

</body>

</html>