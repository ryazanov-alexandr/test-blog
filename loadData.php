<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=blog1', 'root', '');
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Error connection to database');
}

$posts = json_decode(
    file_get_contents('https://jsonplaceholder.typicode.com/posts'),
    true);

$comments = json_decode(
    file_get_contents('https://jsonplaceholder.typicode.com/comments'),
    true);    

$sqlInsertPosts = 'INSERT INTO posts (userId, id, title, body) VALUES (:userId, :id, :title, :body)';
insertArray($sqlInsertPosts, $posts, $pdo);

$sqlInsertComments = 'INSERT INTO comments (postId, id, name, email, body) VALUES (:postId, :id, :name, :email, :body)';
insertArray($sqlInsertComments, $comments, $pdo);

$postsCount = count($posts);
$commentsCount = count($comments);
$text = "Загружено {$postsCount} записей и {$commentsCount} комментариев";
consoleLog($text);


function insertArray(string $sql, array $data, PDO $pdo) {
    $query = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        foreach ($data as $row) {
            $query->execute($row);
        }

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function consoleLog($data) {
    echo("<script>");
    echo("console.log('{$data}')");
    echo("</script>");
}
