<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statements Exercises - PHP Introduction</title>
    <link rel="stylesheet" href="/exercises/css/style.css">
</head>
<body>
    <div class="back-link">
        <a href="index.php">&larr; Back to PHP Introduction</a>
        <a href="/examples/01-php-introduction/02-statements.php">View Example &rarr;</a>
    </div>

    <h1>Statements Exercises</h1>

    <!-- Exercise 1 -->
    <h2>Exercise 1: Age Classifier</h2>
    <p>
        <strong>Task:</strong> 
        Create a variable for age. Use if/else statements to classify and 
        display the age group: "Child" (0-12), "Teenager" (13-19), "Adult" 
        (20-64), or "Senior" (65+).
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $age = rand(0, 100); // Example age, you can change this value
        if ($age >= 0 && $age <= 12) {
            echo "Age: $age - Child";
        } elseif ($age >= 13 && $age <= 19) {
            echo "Age: $age - Teenager";
        } elseif ($age >= 20 && $age <= 64) {
            echo "Age: $age - Adult";
        } elseif ($age >= 65) {
            echo "Age: $age - Senior";
        } else {
            echo "Invalid age.";
        }
        ?>
    </div>

    <!-- Exercise 2 -->
    <h2>Exercise 2: Day of the Week</h2>
    <p>
        <strong>Task:</strong> 
        Create a variable for the day of the week (use a number 1-7). Use 
        a switch statement to display whether it's a "Weekday" or "Weekend", 
        and the day name.
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $dayOfWeek = rand(1, 7); // Example day, you can change this value
        switch ($dayOfWeek) {
            case 1:
                echo "Day: $dayOfWeek - Monday (Weekday)";
                break;
            case 2:
                echo "Day: $dayOfWeek - Tuesday (Weekday)";
                break;
            case 3:
                echo "Day: $dayOfWeek - Wednesday (Weekday)";
                break;
            case 4:
                echo "Day: $dayOfWeek - Thursday (Weekday)";
                break;
            case 5:
                echo "Day: $dayOfWeek - Friday (Weekday)";
                break;
            case 6:
                echo "Day: $dayOfWeek - Saturday (Weekend)";
                break;
            case 7:
                echo "Day: $dayOfWeek - Sunday (Weekend)";
                break;
            default:
                echo "Invalid day.";
        }
        ?>
    </div>

    <!-- Exercise 3 -->
    <h2>Exercise 3: Multiplication Table</h2>
    <p>
        <strong>Task:</strong> 
        Use a for loop to create a multiplication table for a number of your 
        choice (1 through 10). Display each line in the format "X × Y = Z".
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $number = rand(1, 10); // Example number, you can change this value
        echo "Multiplication Table for $number:<br>";
        for ($i = 1; $i <= 10; $i++) {
            $result = $number * $i;
            echo "$number × $i = $result<br>";
        }
        ?>
    </div>

    <!-- Exercise 4 -->
    <h2>Exercise 4: Countdown Timer</h2>
    <p>
        <strong>Task:</strong> 
        Create a countdown from 10 to 0 using a while loop. Display each number, 
        and when you reach 0, display "Blast off!"
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        $countdown = 10; // Starting point
        while ($countdown >= 0) {
            if ($countdown > 0) {
                echo "$countdown...<br>";
            } else {
                echo "Blast off!";
            }
            $countdown--;
        }
        ?>
    </div>

</body>
</html>
