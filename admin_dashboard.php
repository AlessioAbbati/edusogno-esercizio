<?php
session_start();

// Includi il file di connessione al database
include("db_connection.php");
include "header.php";

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
    <link rel="stylesheet" href="assets/styles/style.css?v=<?php echo time(); ?>">

    <title>Pannello di Amministrazione</title>
</head>

<body>
    <h1>Pannello di Amministrazione</h1>
    <!-- <button class="home"><a href="home.php">Pagina personale</a></button> -->

    <?php if ($_SESSION['admin'] === true) { ?>

        <div class="form-box">
            <form class="form-dash" action="admin_dashboard.php" method="post">
                <input type="hidden" name="action" value="add">
                <span class="title">Aggiungi un Evento</span>
                <span class="subtitle">Compila i campi per aggiungere un evento.</span>
                <div class="form-container">
                    <input type="text" class="input-dash" name="nome_evento" placeholder="Nome Evento" required>
                    <input type="text" class="input-dash" name="attendees" placeholder="Attendees (Email)" required>
                    <input type="datetime-local" class="input-dash" name="data_evento" required>
                </div>
                <button class="button" type="submit">Aggiungi Evento</button>
            </form>
        </div>

        <h2>Elenco Eventi</h2>

        <table>
            <thead>
                <tr>
                    <th>Nome Evento</th>
                    <th>Attendees</th>
                    <th>Data e Ora dell'Evento</th>
                    <th>Modifica / Elimina</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventi as $indice => $evento) { ?>
                    <tr>
                        <td><?php echo $evento->getNomeEvento(); ?></td>
                        <td><?php echo $evento->getAttendees(); ?></td>
                        <td><?php echo $evento->getDataEvento(); ?></td>
                        <td class="button-container">
                            <button class="toggle-button button">Gestisci</button>
                            <div class="form-box" style="display: none;">
                                <form class="form-dash" action="admin_dashboard.php" method="post">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id_evento" value="<?php echo $evento->getId(); ?>">
                                    <label for="nome_evento">Nuovo Nome Evento:</label>
                                    <input type="text" class="input-dash" name="nome_evento" value="<?php echo $evento->getNomeEvento(); ?>" placeholder="Nuovo Nome Evento">
                                    <label for="attendees">Nuovi Attendees:</label>
                                    <input type="text" class="input-dash" name="attendees" value="<?php echo $evento->getAttendees(); ?>" placeholder="Nuovi Partecipanti">
                                    <label for="data_evento">Nuova Data e Ora dell'Evento:</label>
                                    <input type="datetime-local" class="input-dash" name="data_evento" value="<?php echo date("Y-m-d\TH:i:s", strtotime($evento->getDataEvento())); ?>" placeholder="Nuova Data e Ora">
                                    <button class="button" type="submit">Modifica</button>
                                </form>
                                <form class="form-dash" action="admin_dashboard.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                                    <input type="hidden" name="id_evento" value="<?php echo $evento->getId(); ?>">
                                    <button class="button" type="submit">Elimina</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


        <script>
            // Aggiungi l'evento click al pulsante "Gestisci"
            const toggleButtons = document.querySelectorAll(".toggle-button");
            toggleButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const formBox = this.nextElementSibling;
                    formBox.style.display = (formBox.style.display === "none" || formBox.style.display === "") ? "block" : "none";
                });
            });
        </script>


    <?php } else { ?>
        <p>Accesso negato. Questa pagina è riservata agli amministratori.</p>
    <?php } ?>
</body>

</html>