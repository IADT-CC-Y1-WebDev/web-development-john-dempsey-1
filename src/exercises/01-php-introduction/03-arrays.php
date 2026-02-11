<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arrays Exercises - PHP Introduction</title>
    <link rel="stylesheet" href="/exercises/css/style.css">
</head>
<body>
    <div class="back-link">
        <a href="index.php">&larr; Back to PHP Introduction</a>
        <a href="/examples/01-php-introduction/03-arrays.php">View Example &rarr;</a>
    </div>

    <h1>Arrays Exercises</h1>

    <!-- Exercise 1 -->
    <h2>Exercise 1: Favorite Movies</h2>
    <p>
        <strong>Task:</strong> 
        Create an indexed array with 5 of your favorite movies. Use a for 
        loop to display each movie with its position (e.g., "Movie 1: 
        The Matrix").
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $movies = [
            "The Matrix", 
            "Inception", 
            "Interstellar", 
            "The Dark Knight", 
            "Pulp Fiction"
        ];
        for ($i = 0; $i < count($movies); $i++) {
            echo "Movie " . ($i + 1) . ": " . $movies[$i] . "<br>";
        }   
        ?>
    </div>

    <!-- Exercise 2 -->
    <h2>Exercise 2: Student Record</h2>
    <p>
        <strong>Task:</strong> 
        Create an associative array for a student with keys: name, studentId, 
        course, and grade. Display this information in a formatted sentence.
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $student = [
            "name" => "Alice Smith",
            "studentId" => "S12345",
            "course" => "Web Development",
            "grade" => "A"
        ];
        echo "Student Name: " . $student["name"] . "<br>";
        echo "Student ID: " . $student["studentId"] . "<br>";
        echo "Course: " . $student["course"] . "<br>";
        echo "Grade: " . $student["grade"];
        ?>
    </div>

    <!-- Exercise 3 -->
    <h2>Exercise 3: Country Capitals</h2>
    <p>
        <strong>Task:</strong> 
        Create an associative array with at least 5 countries as keys and their 
        capitals as values. Use foreach to display each country and capital 
        in the format "The capital of [country] is [capital]."
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $countries = [
            "Ireland" => "Dublin",
            "France" => "Paris",
            "Japan" => "Tokyo",
            "Australia" => "Canberra",
            "Brazil" => "Brasília"
        ];
        foreach ($countries as $country => $capital) {
            echo "The capital of $country is $capital.<br>";
        }
        ?>
    </div>

    <!-- Exercise 4 -->
    <h2>Exercise 4: Menu Categories</h2>
    <p>
        <strong>Task:</strong> 
        Create a nested array representing a restaurant menu with at least 
        2 categories (e.g., "Starters", "Main Course"). Each category should 
        have at least 3 items with prices. Display the menu in an organized 
        format.
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $menu = [
            "Starters" => [
                ["name" => "Bruschetta", "price" => 5.00],
                ["name" => "Garlic Bread", "price" => 3.50],
                ["name" => "Caesar Salad", "price" => 6.00]
            ],
            "Main Course" => [
                ["name" => "Spaghetti Carbonara", "price" => 12.00],
                ["name" => "Grilled Salmon", "price" => 15.00],
                ["name" => "Chicken Parmesan", "price" => 13.50]
            ]
        ];
        foreach ($menu as $category => $items) {
            echo "<strong>$category:</strong><br>";
            foreach ($items as $item) {
                echo "- " . $item["name"] . " ($" . number_format($item["price"], 2) . ")<br>";
            }
            echo "<br>";
        }
        ?>
    </div>

</body>
</html>
