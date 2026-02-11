<?php
namespace College;

require_once __DIR__ . '/Student.php';

class Postgrad extends Student {
    protected $supervisor;
    protected $topic;

    public function __construct($name, $number, $supervisor, $topic) {
        parent::__construct($name, $number);
        $this->supervisor = $supervisor;
        $this->topic = $topic;
    }

    public function getSupervisor() {
        return $this->supervisor;
    }

    public function getTopic() {
        return $this->topic;
    }

    public function __toString() {
        return "Postgrad: {$this->name} ({$this->number}), Supervisor: {$this->supervisor}, Topic: {$this->topic}";
    }
}