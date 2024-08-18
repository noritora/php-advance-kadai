<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

// 登録ボタンを押した時の処理
if (isset($_POST['submit'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);
        // idは自動インクリメントされるから記述しない
        $sql_insert = 'INSERT INTO books (
            book_code, book_name, price, stock_quantity, genre_code, update_at)
            VALUES (:book_code, :book_name, :price, :stock_quantity, :genre_code, :update_at);';
        $stmt_insert = $pdo->prepare($sql_insert);

        // $stmt_insert->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
        $stmt_insert->bindValue('book_name', $_POST['book_name'], PDO::PARAM_STR);
        $stmt_insert->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':update_at', date('Y-m-d H:i:s'), PDO::PARAM_INT);

        $stmt_insert->execute();
        $count = $stmt_insert->rowCount();
        $message = "商品を{$count}件登録しました。";
        header("Location: read.php?message={$message}");

    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

// ジャンルコードの用意
try {
    $pdo = new PDO($dsn, $user, $password);
    $sql_select = 'SELECT genre_code FROM books;';
    $stmt_select = $pdo->query($sql_select);
    $genre_codes = $stmt_select->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    exit($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍管理アプリcreate</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="registration">
            <h1>書籍登録</h1>
            <div class="back">
                <a href="read.php" class="btn">&lt; 戻る</a>
            </div>
            <form action="create.php" method="post" class="registration-form">
                <div>
                    <label for="book_code">書籍コード</label>
                    <input type="number" id="book_code" name="book_code" required>
                    <label for="book_name">書籍名</label>
                    <input type="text" id="book_name" name="book_name" required>
                    <label for="price">単価</label>
                    <input type="number" id="price" name="price" required>
                    <label for="stock_quantity">在庫数</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" required>
                    <label for="genre-code">ジャンルコード</label>
                    <select id="genre_code" name="genre_code" required>
                        <option disabled selected value>選択してください</option>
                        <?php
                        foreach ($genre_codes as $genre_code) {
                            echo "<option value='{$genre_code}'>{$genre_code}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn" name="submit" value="create">登録</button>
            </form>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy;書籍管理アプリ All rights reserved.</p>
    </footer>
</body>
</html>