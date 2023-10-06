<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

include "db_connection.php";
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

                    $email = explode(",", $_POST['attendees']);
                    $newAttendees = implode(", ", $email);

                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->SMTPAuth   = true;
                        $mail->Host       = 'smtp.mailtrap.io'; // Hostname di Mailtrap
                        $mail->Username   = 'f530cb68dd7cef'; // Nome utente di Mailtrap
                        $mail->Password   = '484e6396df6bd3'; // Password di Mailtrap
                        $mail->SMTPSecure = 'tls';
                        $mail->Port       = 2525; // Porta SMTP di Mailtrap
                    
                        $mail->setFrom('from@example.com');
                        foreach ($email as $recipients) {
                            $mail->addAddress($recipients);
                        }
                        $mail->addReplyTo('edusogno@exaple.com');
                    
                        $mail->isHTML(true);
                        $mail->Subject = "Modifica evento";
                        $mail->Body    = <<<EOD
                        Il tuo evento: '$nome_evento', e' stato modificato dall'amministratore. Il nuovo evento e' il seguente:<br>
                        NOME EVENTO: '$nome_evento'<br>
                        PARTECIPANTI: '$newAttendees'<br>
                        DATA EVENTO: '$data_evento'
                        EOD;
                        
                        $mail->send();
                        ?>
                            <?php echo 'Il messaggio è stato inviato con successo ai partecipanti'; header('refresh:2'); ?>
                        <?php 
                        
                    } catch (Exception $e) {
                        ?>
                            <?php echo "Impossibile inviare il messaggio. Errore Mailer: {$mail->ErrorInfo}"; ?>
                        <?php 
                    }
                }
                break;

            case 'delete':
                if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                    if (isset($_POST['id_evento'])) {
                        $idDaEliminare = $_POST['id_evento'];
                        
                        $mysqli = "SELECT * FROM eventi WHERE id='$idDaEliminare'";
                        $result = mysqli_query($conn, $mysqli);
                        $row = mysqli_fetch_assoc($result);

                        $email = explode(",", $row['attendees']);
                        $nome_evento = $row['nome_evento'];
                        

                        $mail = new PHPMailer(true);

                        try {
                            $mail->isSMTP();
                            $mail->SMTPAuth   = true;
                            $mail->Host       = 'smtp.mailtrap.io'; // Hostname di Mailtrap
                            $mail->Username   = 'f530cb68dd7cef'; // Nome utente di Mailtrap
                            $mail->Password   = '484e6396df6bd3'; // Password di Mailtrap
                            $mail->SMTPSecure = 'tls';
                            $mail->Port       = 2525; // Porta SMTP di Mailtrap

                            $mail->setFrom('from@example.com');
                            foreach ($email as $recipients) {
                                $mail->addAddress($recipients);
                            }
                            $mail->addReplyTo('edusogno@exaple.com');

                            $mail->isHTML(true);
                            $mail->Subject = "Cancellazione evento";
                            $mail->Body    = <<<EOD
                            Il tuo evento: '$nome_evento', e' stato cancellato:<br>
                            EOD;
                            
                            $mail->send();
                            ?>
                                <?php echo 'Il messaggio è stato inviato con successo ai partecipanti'; header('refresh:2'); ?>
                            <?php 

                        } catch (Exception $e) {
                            ?>
                                <?php echo "Impossibile inviare il messaggio. Errore Mailer: {$mail->ErrorInfo}"; ?>
                            <?php 
                        
                        }
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
                            <form class="form-dash" action="edit.php" method="get">
                                <button class="toggle-button button">Modifica</button>
                                <input type="hidden" name="id_evento" value="<?php echo $evento->getId(); ?>">
                            </form>
                            
                            <form class="form-dash" action="admin_dashboard.php" method="post" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                                <input type="hidden" name="id_evento" value="<?php echo $evento->getId(); ?>">
                                <button class="button" type="submit">Elimina</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


    <?php } else { ?>
        <p>Accesso negato. Questa pagina è riservata agli amministratori.</p>
    <?php } ?>
</body>

</html>