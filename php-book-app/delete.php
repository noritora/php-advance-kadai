<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    $sql = 'DELETE FROM books WHERE id = :id;';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->rowCount();
    $message = "レコードを{$count}件削除しました。";
    header("Location: read.php?message={$message}");
} catch (PDOException $e) {
    exit($e->getMessage());
}

?>