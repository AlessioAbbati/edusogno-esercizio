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
        $evento = new Evento(null, $nome_evento, $attendees, $data_evento); // Rimuovi $id
        $this->eventi[] = $evento;
    
        // Inserisci l'evento nel database
        $this->inserisciEventoNelDatabase($nome_evento, $attendees, $data_evento); // Rimuovi $id
    }
    
    
    

    public function getEventi() {
        $eventi = array();
    
        // Query SQL per recuperare gli eventi dal database
        $sql = "SELECT * FROM eventi";
        $result = mysqli_query($this->conn, $sql);
    
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Creazione di oggetti Evento e aggiunta all'array $eventi
                $evento = new Evento($row['id'], $row['nome_evento'], $row['attendees'], $row['data_evento']);
                $eventi[] = $evento;
            }
            mysqli_free_result($result);
        }
    
        return $eventi;
    }

    public function modificaEvento($id_evento, $nome_evento, $attendees, $data_evento) {
        // Aggiorna l'evento nel database
        $this->aggiornaEventoNelDatabase($id_evento, $nome_evento, $attendees, $data_evento);
    }
    

    private function aggiornaEventoNelDatabase($id_evento, $nome_evento, $attendees, $data_evento) {
        // Query SQL per aggiornare l'evento nel database
        $sql = "UPDATE eventi SET nome_evento = '$nome_evento', attendees = '$attendees', data_evento = '$data_evento' WHERE id = $id_evento";
    
        if (mysqli_query($this->conn, $sql)) {
            echo "Evento aggiornato con successo nel database.";
        } else {
            echo "Errore nell'aggiornamento dell'evento nel database: " . mysqli_error($this->conn);
        }
    }

    public function eliminaEvento($idDaEliminare) {
        // Elimina l'evento dalla lista (se esiste)
        foreach ($this->eventi as $indice => $evento) {
            if ($evento->getId() === $idDaEliminare) {
                unset($this->eventi[$indice]);
                break; // Esci dal ciclo una volta trovato l'evento da eliminare
            }
        }
    
        // Elimina l'evento dal database
        $this->eliminaEventoDalDatabase($idDaEliminare);
    }
    
    
    private function trovaIdEventoDalDatabase($evento) {
        // Query SQL per trovare l'ID dell'evento in base a nome e data
        $sql = "SELECT id FROM eventi WHERE nome_evento = '{$evento->getNomeEvento()}' AND data_evento = '{$evento->getDataEvento()}'";
    
        $result = mysqli_query($this->conn, $sql);
    
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['id'];
        }
    
        return false;
    }
    
    private function eliminaEventoDalDatabase($idDaEliminare) {
        // Query SQL per eliminare l'evento dal database
        $sql = "DELETE FROM eventi WHERE id = $idDaEliminare";
        
        if (mysqli_query($this->conn, $sql)) {
            echo "Evento eliminato con successo dal database.";
        } else {
            echo "Errore nell'eliminazione dell'evento dal database: " . mysqli_error($this->conn);
        }
    }
    

    private function inserisciEventoNelDatabase($nome_evento, $attendees, $data_evento) {
        // Configurazione del database
        $sname = "localhost";
        $uname = "root";
        $password = "root";
        $db_name = "edusogno";
        $data_evento = date("Y-m-d H:i:s", strtotime($data_evento));

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
