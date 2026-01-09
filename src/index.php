<?php
$server = "mysql-container";
$username = "testuser";
$password = "mysecret";

try {
    // Connect to the notes database
    $dbname = "notesdb";
    $conn = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("SELECT * FROM notes");
    $stmt->execute();

    // Fetch all notes
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Connect to the tasks database
    $dbname = "tasksdb";
    $conn = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("SELECT * FROM tasks");
    $stmt->execute();

    // Fetch all tasks
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
catch (PDOException $e) {
    die("<p>PDO Exception: " . $e->getMessage() . "</p>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello, World!</title>
</head>
<body>
    <h1>Notes</h1>
    <ul>
        <?php foreach ($notes as $row): ?>
            <li><?php echo htmlspecialchars($row['body']); ?></li>
        <?php endforeach; ?>
    </ul>
    <h1>Tasks</h1>
    <ul>
        <?php foreach ($tasks as $row): ?>
            <li><?php echo htmlspecialchars($row['body']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>