<?php

if ($_POST['search'] === '') {
    exit(json_encode([]));
} 

try {
    $pdo = new PDO('mysql:host=localhost;dbname=blog1', 'root', '');
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(json_encode('Error connection to database'));
}

$text = "%{$_POST['search']}%";
$sql = "
    SELECT p.title, c.body 
    FROM posts p
    JOIN comments c
    ON c.postId = p.id  
    WHERE c.body LIKE :text
";
$preparedStatement = $pdo->prepare($sql);
$preparedStatement->execute([':text' => $text]);
$comments = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
echo(json_encode($comments));