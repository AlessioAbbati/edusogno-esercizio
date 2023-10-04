<?php
session_start();

// Includi il file di connessione al database
include("db_connection.php");

// Simula l'autenticazione dell'amministratore (personalizza questa logica)
$_SESSION['admin'] = true;

// Include le classi Event e EventController
include("evento.php");
include("eventoController.php");

$controllerEvento = new EventoController($conn); // Passa la connessione al costruttore

// Gestisci le azioni dell'amministratore
if ($_SESSION['admin'] === true) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'add':
                if (isset($_POST['nome_evento']) && isset($_POST['attendees']) && isset($_POST['data_evento'])) {
                    $nome_evento = $_POST['nome_evento'];
                    $attendees = $_POST['attendees'];
                    $data_evento = $_POST['data_evento'];
                    $controllerEvento->aggiungiEvento($nome_evento, $attendees, $data_evento);
                }
                break;

            case 'edit':
                if (isset($_POST['id_evento']) && isset($_POST['nome_evento']) && isset($_POST['attendees']) && isset($_POST['data_evento'])) {
                    $id_evento = $_POST['id_evento'];
                    $nome_evento = $_POST['nome_evento'];
                    $attendees = $_POST['attendees'];
                    $data_evento = $_POST['data_evento'];
                    $controllerEvento->modificaEvento($id_evento, $nome_evento, $attendees, $data_evento);
                }
                break;

            case 'delete':
                if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                    if (isset($_POST['id_evento'])) {
                        $idDaEliminare = $_POST['id_evento'];
                        $controllerEvento->eliminaEvento($idDaEliminare);
                    }
                }
                break;
        }
    }
}

$eventi = $controllerEvento->getEventi();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="assets/styles/style.css"> -->
    <title>Pannello di Amministrazione</title>
</head>

<body>
    <h1>Pannello di Amministrazione</h1>
    <a href="home.php">Pagina personale</a>

    <?php if ($_SESSION['admin'] === true) { ?>
        <h2>Aggiungi un Evento</h2>
        <form action="admin_dashboard.php" method="post">
            <input type="hidden" name="action" value="add">
            <label>Nome Evento:</label>
            <input type="text" name="nome_evento" required><br>
            <label>Attendees (Email):</label>
            <input type="email" name="attendees" required><br>
            <label>Data e Ora dell'Evento:</label>
            <input type="datetime-local" name="data_evento" required><br>
            <button type="submit">Aggiungi Evento</button>
        </form>



        <h2>Elenco Eventi</h2>
        <ul>
    <?php foreach ($eventi as $indice => $evento) { ?>
        <li>
            <strong>Nome Evento:</strong> <?php echo $evento->getNomeEvento(); ?><br>
            <strong>Attendees:</strong> <?php echo $evento->getAttendees(); ?><br>
            <strong>Data e Ora dell'Evento:</strong> <?php echo $evento->getDataEvento(); ?><br>
            <form action="admin_dashboard.php" method="post" style="display: inline;">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_evento" value="<?php echo $evento->getId(); ?>">
                <label for="nome_evento">Nuovo Nome Evento:</label>
                <input type="text" name="nome_evento" value="<?php echo $evento->getNomeEvento(); ?>" placeholder="Nuovo Nome Evento">
                <label for="attendees">Nuovi Attendees:</label>
                <input type="email" name="attendees" value="<?php echo $evento->getAttendees(); ?>" placeholder="Nuovi Partecipanti">
                <label for="data_evento">Nuova Data e Ora dell'Evento:</label>
                <input type="datetime-local" name="data_evento" value="<?php echo date("Y-m-d\TH:i:s", strtotime($evento->getDataEvento())); ?>" placeholder="Nuova Data e Ora">
                <button type="submit">Modifica</button>
            </form>


            <form action="admin_dashboard.php" method="post" style="display: inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                <input type="hidden" name="id_evento" value="<?php echo $evento->getId(); ?>">
                <button type="submit">Elimina</button>
            </form>
        </li>
    <?php } ?>
</ul>


    <?php } else { ?>
        <p>Accesso negato. Questa pagina è riservata agli amministratori.</p>
    <?php } ?>
</body>

</html>
