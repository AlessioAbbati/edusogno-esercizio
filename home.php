<?php
include "db_connection.php";
include "header.php";
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['nome']) && isset($_SESSION['cognome']) && isset($_SESSION['email'])) {

    // Query SQL per verificare se l'utente è un amministratore
    $userEmail = $_SESSION['email'];
    $sqlAdminCheck = "SELECT admin FROM utenti WHERE email = '$userEmail'";
    $resultAdminCheck = mysqli_query($conn, $sqlAdminCheck);

    if ($resultAdminCheck) {
        $rowAdminCheck = mysqli_fetch_assoc($resultAdminCheck);
        $isAdmin = $rowAdminCheck['admin'];
    } else {
        echo "Errore nella query di verifica admin: " . mysqli_error($conn);
        exit(); // Gestisci l'errore come preferisci
    }

    // Query SQL per recuperare gli eventi
    if ($isAdmin) {
        // Se l'utente è un amministratore, ottenere tutti gli eventi senza distinzione
        $sqlEvents = "SELECT * FROM eventi";
    } else {
        // Altrimenti, ottenere solo gli eventi associati all'email dell'utente
        $sqlEvents = "SELECT * FROM eventi WHERE attendees LIKE '%$userEmail%'";
    }

    $resultEvents = mysqli_query($conn, $sqlEvents);

    if ($resultEvents) {
        // Il resto del tuo codice per visualizzare gli eventi rimane invariato
        // ...
    } else {
        echo "Errore nella query degli eventi: " . mysqli_error($conn);
        exit(); // Gestisci l'errore come preferisci
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/styles/homeStyle.css">
        <title>Home</title>
    </head>

    <body>
        <h1>Ciao, <?php echo $_SESSION['nome']; ?> <?php echo $_SESSION['cognome']; ?> ecco i tuoi eventi</h1>
        <div class="buttons">
            <button><a href="logout.php">Logout</a></button>

            <?php
            if ($isAdmin) {
            ?>
                <button><a href="admin_dashboard.php">Pannello di Amministrazione</a></button>
            <?php
            } else {
            ?>
                <button><a href="javascript:void(0)" onclick="alert('Non sei un amministratore')">Pannello di Amministrazione</a></button>
            <?php
            }
            ?>
        </div>

        <?php
        // Verifica se ci sono eventi da mostrare
        if (mysqli_num_rows($resultEvents) > 0) {
            // Ci sono eventi, stampali come card
        ?> <div class="cards"> <?php
                                    while ($row = mysqli_fetch_assoc($resultEvents)) {
                                        // L'evento contiene l'email dell'utente loggato, stampalo
                                    ?>

                    <div class="card-body">
                        <h2 class="card-title"><?php echo $row['nome_evento']; ?></h2>
                        <p class="card-text">Data: <?php echo $row['data_evento']; ?></p>
                        <button>JOIN</button>
                    </div>

                <?php
                                    } ?>
            </div> <?php
                    // Rilascia la risorsa del risultato
                    mysqli_free_result($resultEvents);
                } else {
                    // Nessun evento
                    echo "Nessun evento";
                }
                    ?>

    </body>

    </html>
<?php
} else {
    // Utente non autenticato, gestisci il caso in base alle tue esigenze
}
?>