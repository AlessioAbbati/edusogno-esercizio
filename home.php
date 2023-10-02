<?php
include "db_connection.php";
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {

    // Query SQL per recuperare gli eventi dell'utente autenticato
    $userEmail = $_SESSION['email'];
    $sql = "SELECT * FROM eventi WHERE attendees LIKE '%$userEmail%'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/style.css">
    <title>Home</title>
</head>

<body>
    <h1>Ciao, <?php echo $_SESSION['nome']; ?> <?php echo $_SESSION['cognome']; ?> ecco i tuoi eventi</h1>

    <?php
        // Stampare gli eventi come card
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $row['nome_evento']; ?></h5>
            <p class="card-text">Data: <?php echo $row['data_evento']; ?></p>
            <!-- Altre informazioni sugli eventi possono essere stampate qui -->
        </div>
    </div>
    <?php
        }
        // Rilascia la risorsa del risultato
        mysqli_free_result($result);
    } else {
        echo "Errore nella query: " . mysqli_error($conn);
    }
    ?>

</body>

</html>
<?php
}
?>





