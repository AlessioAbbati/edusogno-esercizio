<?php

class Evento {
    private $id; // Aggiunto un campo id
    private $nome_evento;
    private $attendees;
    private $data_evento;

    public function __construct($id, $nome_evento, $attendees, $data_evento) {
        $this->id = $id;
        $this->nome_evento = $nome_evento;
        $this->attendees = $attendees;
        $this->data_evento = $data_evento;
    }

    public function getId() {
        return $this->id;
    }

    public function setNomeEvento($nome_evento) {
        $this->nome_evento = $nome_evento;
    }

    public function getNomeEvento() {
        return $this->nome_evento;
    }

    public function setAttendees($attendees) {
        $this->attendees = $attendees;
    }

    public function getAttendees() {
        return $this->attendees;
    }

    public function setDataEvento($data_evento) {
        $this->data_evento = $data_evento;
    }

    public function getDataEvento() {
        return $this->data_evento;
    }

}

