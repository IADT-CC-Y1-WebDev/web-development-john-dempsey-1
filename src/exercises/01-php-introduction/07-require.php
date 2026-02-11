<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libraries Exercises - PHP Introduction</title>
    <link rel="stylesheet" href="/exercises/css/style.css">
</head>
<body>
    <div class="back-link">
        <a href="index.php">&larr; Back to PHP Introduction</a>
        <a href="/examples/01-php-introduction/07-require.php">View Example &rarr;</a>
    </div>

    <h1>Libraries Exercises</h1>

    <!-- Exercise 1 -->
    <h2>Exercise 1: Create a Navigation Include</h2>
    <p>
        <strong>Task:</strong> Create a file called inc/navigation.php that contains 
        an HTML navigation menu with at least 4 links. Then use require to include it 
        in this page twice (at the top and bottom).
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        require 'inc/navigation.php';
        ?>
    </div>

    <!-- Exercise 2 -->
    <h2>Exercise 2: Create a Utility Library</h2>
    <p>
        <strong>Task:</strong> Create a file called lib/utilities.php with three 
        functions: 
        truncate($text, $length) - truncates text to specified length, 
        formatPrice($amount) - formats a number as currency, 
        getCurrentYear() - returns current year. 
        Use require_once to import it and test all functions.
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        require_once 'lib/utilities.php';
        // Test truncate function
        $sampleText = "The quick brown fox jumps over the lazy dog.";
        echo "Truncated Text: " . truncate($sampleText, 20) . "<br>";
        // Test formatPrice function
        $price = 1234.5;
        echo "Formatted Price: " . formatPrice($price) . "<br>";
        // Test getCurrentYear function
        echo "Current Year: " . getCurrentYear() . "<br>";
        ?>
    </div>

    <!-- Exercise 3 -->
    <h2>Exercise 3: Multiple Libraries</h2>
    <p>
        <strong>Task:</strong> Create two library files: 
        lib/validators.php (with a function isValidEmail($email)) and 
        lib/formatters.php (with a function formatPhoneNumber($number)). 
        Import both using require_once and demonstrate using both functions.
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here
        require_once 'lib/validators.php';
        require_once 'lib/formatters.php';
        // Test isValidEmail function
        $email = "joe@bloggs.com";
        echo "Is '$email' a valid email? " . (isValidEmail($email) ? "Yes" : "No") . "<br>";
        // Test formatPhoneNumber function
        $phoneNumber = "123-456-7890";
        echo "Formatted Phone Number: " . formatPhoneNumber($phoneNumber) . "<br>";
        ?>
    </div>

</body>
</html>
