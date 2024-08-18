<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    } else {
        $order = NULL;
    }

    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
    } else {
        $keyword = NULL;
    }

    if ($order === 'desc') {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY update_at DESC;';
    } else {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY update_at ASC;';
    }

    // $sql = 'SELECT * FROM books;';
    // $stmt = $pdo->query($sql);
    $stmt_select = $pdo->prepare($sql_select);
    $prepare_match = "%{$keyword}%";
    $stmt_select->bindValue(':keyword', $prepare_match, PDO::PARAM_STR);
    $stmt_select->execute();
    $products = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍管理アプリread</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="products">
            <h1>書籍一覧</h1>
            <?php
            if (isset($_GET['message'])) {
                echo "<p >{$_GET['message']}</p>";
            }
            ?>
            <div class="products-ui">
                <div>
                    <a href="read.php?order=desc&keyword=<?= $keyword ?>">
                        <img src="images/desc.png" alt="降順" class="sort-img">
                    </a>
                    <a href="read.php?order=asc&keyword=<?= $keyword ?>">
                        <img src="images/asc.png" alt="昇順" class="sort-img">
                    </a>
                    <form method="get" action="read.php" class="search-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" placeholder="書籍名で検索" class="search-box" name="keyword" value="<?= $keyword?>">
                    </form>
                </div>
                <a href="create.php" class="btn">書籍登録</a>
            </div>
            <table class="products-table">
                <tr>
                    <th>書籍コード</th>
                    <th>書籍名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>ジャンルコード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php
                foreach ($products as $product) {
                    $table_row = "
                    <tr>
                    <td>{$product['book_code']}</td>
                    <td>{$product['book_name']}</td>
                    <td>{$product['price']}</td>
                    <td>{$product['stock_quantity']}</td>
                    <td>{$product['genre_code']}</td>
                    <td><a href='update.php?id={$product['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
                    <td><a href='delete.php?id={$product['id']}'><img src='images/delete.png' alt='削除' class='delete-icon'></a></td>
                    </tr>
                    ";
                    echo $table_row;
                }
                ?>
            </table>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy;書籍管理アプリ All rights reserved.</p>
    </footer>
</body>
</html>