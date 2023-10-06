<?php

session_start();

// Includi il file di connessione al database
include("db_connection.php");
include "header.php";

if(isset($_GET['id_evento'])) {
    $id_evento = $_GET['id_evento'];

    $sqlId = "SELECT * FROM eventi WHERE id='$id_evento'";
    $result = mysqli_query($conn, $sqlId);
    $row = mysqli_fetch_assoc($result);
} else {
    ?><div>
      <?php  echo "nessun evento da modificare"; ?>
    </div><?php
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/style.css?v=<?php echo time(); ?>">
    <title>Modifica eventi</title>
</head>

<body>
    <h1>Modifica eventi</h1>
    <div class="form-box">
    <form class="form-dash" action="admin_dashboard.php" method="post">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id_evento" value="<?php echo $row['id']; ?>">
        <label for="nome_evento">Nuovo Nome Evento:</label>
        <input type="text" class="input-dash" name="nome_evento" value="<?php echo $row['nome_evento']; ?>" placeholder="Nuovo Nome Evento">
        <label for="attendees">Nuovi Attendees:</label>
        <input type="text" class="input-dash" name="attendees" value="<?php echo $row['attendees']; ?>" placeholder="Nuovi Partecipanti">
        <label for="data_evento">Nuova Data e Ora dell'Evento:</label>
        <input type="datetime-local" class="input-dash" name="data_evento" value="<?php echo date("Y-m-d\TH:i:s", strtotime($row['data_evento'])); ?>" placeholder="Nuova Data e Ora">
        <button class="button" type="submit">Modifica</button>
    </form>
    </div>
</body>

</html>