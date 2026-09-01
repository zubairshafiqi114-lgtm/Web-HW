PHP OOP Lab 03
Lab Assignment: PHP OOP Building Blocks
Student ID: 28
The PHP code below contains Task 1, Task 2, and Task 3 as required.
<?php
// Full Name: Zobair Shafiqi
// Student ID: 28
// PHP OOP Lab 03 - Building Blocks

// Task 1
class Library
{
    // This is a constant because the maximum number of books is a fixed rule of the library.
    const MAX_BOOKS = 3;
}

echo "Maximum books allowed: " . Library::MAX_BOOKS . "<br>";

// Task 2
class StudentCounter
{
    public static $count = 0;

    public static function addStudent()
    {
        self::$count++;
    }
}

StudentCounter::addStudent();
StudentCounter::addStudent();
StudentCounter::addStudent();

echo "Total students: " . StudentCounter::$count . "<br>";

// Task 3
abstract class Vehicle
{
    abstract public function start();
}

class Car extends Vehicle
{
    public function start()
    {
        echo "Car engine started.<br>";
    }
}

class Bike extends Vehicle
{
    public function start()
    {
        echo "Bike started.<br>";
    }
}

$car = new Car();
$bike = new Bike();

$car->start();
$bike->start();
?>

