<?php
namespace College;

class Student {
    // private static $count = 0;
    private static $students = [];

    public static function getCount() {
        return count(self::$students);
    }

    public static function findAll() {
        return self::$students;
    }

    public static function findByNumber($num) {
        return self::$students[$num] ?? null;
    }

    protected $name;
    protected $number;

    public function __construct($name, $number) {
        if (empty($number)) {
            throw new Exception("Student number cannot be empty");
        }
        $this->name = $name;
        $this->number = $number;

        echo "Student created: " . $this->name . " with number " . $this->number . "<br>";

        // self::$count++;
        self::$students[$number] = $this;
    }

    public function getName() {
        return $this->name;
    }

    public function getNumber() {
        return $this->number;
    }

    public function __toString() {
        return "Name: {$this->name}, Number: {$this->number}";
    }

    public function leave() {
        echo "Student leaving: " . $this->name . "<br>";
        unset(self::$students[$this->number]);
    }

    public function __destruct() {
        echo "Destroying student: " . $this->name . "<br>";
    }
}