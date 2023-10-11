<?php
require_once(dirname(__FILE__) . '/../function.php');
try {
    $err = array();
    session_start();
    //DBに接続
    $pdo = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';', DB_USER, DB_PASSWORD);
    $pdo->query('SET NAMES utf8;');
    
    if (isset($_SESSION['USER'])) {
        header('Location:/reserve/web/admin/reserve_list.php');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //入力値を取得
        $login_id = $_POST['login_id'];
        $login_password = $_POST['login_password'];

        if (!$login_id) {
            $err['login_id'] = 'IDを入力してください。';
        }

        if (!$login_password) {
            $err['login_password'] = 'パスワードを入力してください。';
        }

        if (empty($err)) {
            $sql = "SELECT * FROM shop WHERE login_id = :login_id AND login_password = :login_password LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':login_id', $login_id, PDO::PARAM_STR);
            $stmt->bindValue(':login_password', $login_password, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['USER'] = $user;
                header('Location: /reserve/web/admin/reserve_list.php');
                exit;

            } else {
                $err['common'] = "認証に失敗しました";
            }
        }
    } else {
        $login_id = "";
        $login_password = "";
    }
} catch (Exception $e) {
    header('Location: /error.php');
    exit;
}
?>
<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!--Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Original CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <title>予約システムログイン</title>
</head>

<body>
    <header>SHOP</header>

    <h1>予約システムログイン</h1>

    <form class="card text-center" method="POST">
        <?php if (isset($err['common'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= $err['common'] ?>
            </div>
        <?php endif; ?>
        <div class="card-body">
            <div class="mb-3 text-start">
                <input type="text" class="form-control <?php if (isset($err['login_id']))
                    echo 'is-invalid' ?>" id="login_id" name="login_id" placeholder="ID" value="<?= $login_id ?>">
                <div class="invalid-feedback">
                    <?= $err['login_id'] ?>
                </div>
            </div>
            <div class="mb-3 text-start">
                <input type="password" class="form-control <?php if (isset($err['login_password']))
                    echo 'is-invalid' ?>" id="login_password" name="login_password" placeholder="PASSWORD">
                    <div class="invalid-feedback">
                    <?= $err['login_password'] ?>
                </div>
            </div>
            <div class="d-grid gap-2 my-3">
                <button class="btn btn-primary rounded-pill" type="submit">ログイン</button>
                <div>
                </div>
            </div>
            <div>
    </form>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>
</html>