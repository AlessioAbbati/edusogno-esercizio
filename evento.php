<?php

class Evento {
    public $attendees;
    public $nome_evento;
    public $data_evento;

    public function __construct($attendees, $nome_evento, $data_evento) {
        $this->attendees = $attendees;
        $this->nome_evento = $nome_evento;
        $this->data_evento = $data_evento;
    }

    public function getAttendees() {
        return $this->attendees;
    }

    public function setAttendees($attendees) {
        $this->attendees = $attendees;
    }

    public function getNomeEvento() {
        return $this->nome_evento;
    }

    public function setNomeEvento($nome_evento) {
        $this->nome_evento = $nome_evento;
    }

    public function getDataEvento() {
        return $this->data_evento;
    }

    public function setDataEvento($data_evento) {
        $this->data_evento = $data_evento;
    }
}

