<?php

class EventoController {
    private $eventi = [];
    private $conn;

    public function __construct($conn) {
        // Iniziamo con una lista vuota di eventi
        $this->eventi = [];
        $this->conn = $conn;
    }

    public function aggiungiEvento($nome_evento, $attendees, $data_evento) {
        // Crea una nuova istanza di Evento e aggiungila alla lista degli eventi
        $evento = new Evento($nome_evento, $attendees, $data_evento);
        $this->eventi[] = $evento;

        // Inserisci l'evento nel database
        $this->inserisciEventoNelDatabase($nome_evento, $attendees, $data_evento);
    }

    public function getEventi() {
        $eventi = array();
    
        // Query SQL per recuperare gli eventi dal database
        $sql = "SELECT * FROM eventi";
        $result = mysqli_query($this->conn, $sql);
    
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Creazione di oggetti Evento e aggiunta all'array $eventi
                $evento = new Evento($row['nome_evento'], $row['attendees'], $row['data_evento']);
                $eventi[] = $evento;
            }
            mysqli_free_result($result);
        }
    
        return $eventi;
    }

    public function modificaEvento($indice, $nome_evento, $attendees, $data_evento) {
        if (isset($this->eventi[$indice])) {
            // Modifica l'evento esistente
            $this->eventi[$indice]->setNomeEvento($nome_evento);
            $this->eventi[$indice]->setAttendees($attendees);
            $this->eventi[$indice]->setDataEvento($data_evento);
        }
    }

    public function eliminaEvento($indice) {
        if (isset($this->eventi[$indice])) {
            // Rimuovi l'evento dalla lista
            array_splice($this->eventi, $indice, 1);
        }
    }

    private function inserisciEventoNelDatabase($nome_evento, $attendees, $data_evento) {
        // Configurazione del database
        $sname = "localhost";
        $uname = "root";
        $password = "root";
        $db_name = "edusogno";

        // Connessione al database
        $conn = mysqli_connect($sname, $uname, $password, $db_name);

        // Verifica la connessione al database
        if (!$conn) {
            echo "Connessione al database fallita: " . mysqli_connect_error();
            return;
        }

        // Escape dei dati per prevenire SQL injection
        $nome_evento = mysqli_real_escape_string($conn, $nome_evento);
        $attendees = mysqli_real_escape_string($conn, $attendees);
        $data_evento = mysqli_real_escape_string($conn, $data_evento);

        // Query per l'inserimento dell'evento nel database
        $sql = "INSERT INTO eventi (nome_evento, attendees, data_evento) VALUES ('$nome_evento', '$attendees', '$data_evento')";

        // Esegui la query
        if (mysqli_query($conn, $sql)) {
            echo "Nuovo evento inserito nel database con successo.";
        } else {
            echo "Errore nell'inserimento dell'evento nel database: " . mysqli_error($conn);
        }

        // Chiudi la connessione al database
        mysqli_close($conn);
    }
}
?>
