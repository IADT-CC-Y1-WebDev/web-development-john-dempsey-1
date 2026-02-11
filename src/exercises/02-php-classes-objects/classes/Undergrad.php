<?php
require_once __DIR__ . '/Student.php';

class Undergrad extends Student {
    protected $course;
    protected $year;

    public function __construct($name, $number, $course, $year) {
        parent::__construct($name, $number);
        $this->course = $course;
        $this->year = $year;
    }

    public function getCourse() {
        return $this->course;
    }

    public function getYear() {
        return $this->year;
    }

    public function __toString() {
        return "Undergrad: {$this->name} ({$this->number}), {$this->course}, Year {$this->year}";
    }
}