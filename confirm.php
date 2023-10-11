<?php
require_once 'function.php';

session_start();

//DBに接続
$pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';', DB_USER, DB_PASSWORD);
$pdo->query('SET NAMES utf8;');

//セッションデータを取得
$stmt = $pdo->prepare ('SELECT * FROM shop WHERE id = :id');
$stmt->bindValue(':id', 1, PDO::PARAM_INT);
$stmt->execute();
$shop = $stmt->fetch();


//予約確定ボタンが押された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // セッションから入力情報を取得する
    if (isset($_SESSION['RESERVE'])) {
    $reserve_date= $_SESSION['RESERVE']['reserve_date'];
    $reserve_num= $_SESSION['RESERVE']['reserve_num'];
    $reserve_time = $_SESSION['RESERVE']['reserve_time'];
    $name = $_SESSION['RESERVE']['name'];
    $email = $_SESSION['RESERVE']['email'];
    $tel = $_SESSION['RESERVE']['tel'];
    $comment = $_SESSION['RESERVE']['comment'];

    $stmt = $pdo->prepare("SELECT SUM(reserve_num) FROM reserve WHERE DATE_FORMAT(reserve_date, '%Y%m%d') = :reserve_date AND DATE_FORMAT(reserve_time, '%H:%i') = :reserve_time GROUP BY reserve_date, reserve_time LIMIT 1;");
    $stmt->bindValue(':reserve_date',$reserve_date, PDO::PARAM_STR);
    $stmt->bindValue(':reserve_time',$reserve_time, PDO::PARAM_STR);
    $stmt->execute();
    $reserve_count = $stmt->fetchColumn();
    
    if($reserve_count && ($reserve_count + $reserve_num) > $shop['max_reserve_num']) {
        $err['common'] = 'この日時はすでに予約が埋まっております。<br> 予約画面に戻って予約情報を変更してください。';
    }

    if(empty($err)){
    
        //reserveテーブルにinsert
        $stmt = $pdo->prepare ('INSERT INTO reserve (reserve_date, reserve_time, reserve_num, name, email, tel, comment) VALUE (:reserve_date, :reserve_time, :reserve_num, :name, :email, :tel, :comment)');
        $stmt->bindValue(':reserve_date', $reserve_date, PDO::PARAM_STR);
        $stmt->bindValue(':reserve_time', $reserve_time, PDO::PARAM_STR);
        $stmt->bindValue(':reserve_num', $reserve_num, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
        $stmt->execute();

        //予約者にメール送信
        $from = 'From: web予約システムReserve<'. ADMIN_EMAIL .'>';
        
        $view_reserve_date = format_date($reserve_date);

        $subject = 'ご予約が確定しました。 ';
        $body = <<<EOT
    {$name}様

    以下の内容でご予約を承りました。

    ご予約内容
    [日時] { $view_reserve_date} {$reserve_time}
    [人数]{$reserve_num}人
    [氏名] {$name}
    [メールアドレス]{$email}
    [電話番号]{$tel}
    [備考]{$comment}

    ご来店をお待ちしております。
    EOT;
        mb_send_mail($email, $subject, $body, $from);
    //     $subject = '[Reserve] 予約が確定しました。 ';
    //     $body = <<<EOT
    // {$name}様

    // 以下の内容で予約が確認しました。

    // ご予約内容
    // [日時] { $view_reserve_date} {$reserve_time}
    // [人数]{$reserve_num}人
    // [氏名] {$name}
    // [メールアドレス]{$email}
    // [電話番号]{$tel}
    // [備考]{$comment}
    // EOT;

        //mb_send_mail(ADMIN_EMAIL, $subject, $body, $from);
        // 予約が正常に完了したらセッションのデータをクリアする
        unset($_SESSION['RESERVE']);
        //DBから切断
        unset($pdo);

        //予約完了画面の表示
        header('Location: /reserve/web/complete.php');
        exit;
    }
    } else {
         // セッションからデータが取得出来ない場合はエラー

    }
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

    <!-- Original CSS -->
    <link href="css/style.css" rel="stylesheet">
    <title>予約内容確認</title>
</head>

<body>
    <header>SHOP</header>

    <h1>予約内容確認</h1>
    <form method="post">
        <?php if(isset($err['common'])):?>
            <div class="alert alert-danger" role="alert"><?= $err['common'] ?></div>
        <?php endif; ?>  
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">日時</th>
                    <td><?= format_date($_SESSION['RESERVE']['reserve_date'])?> <?= $_SESSION['RESERVE']['reserve_time']?></td>
                </tr>
                <tr>
                    <th scope="row">人数</th>
                    <td><?= $_SESSION['RESERVE']['reserve_num']?>名</td>
                </tr>
                <tr>
                    <th scope="row">氏名</th>
                    <td colspan="2"><?= $_SESSION['RESERVE']['name']?></td>
                </tr>
                <tr>
                    <th scope="row">メールアドレス</th>
                    <td colspan="2"><?= $_SESSION['RESERVE']['email']?></td>
                </tr>
                <tr>
                    <th scope="row">電話番号</th>
                    <td colspan="2"><?= $_SESSION['RESERVE']['tel']?></td>
                </tr>
                <tr>
                    <th scope="row">備考欄</th>
                    <td colspan="2"><?= nl2br($_SESSION['RESERVE']['comment'])?></td>
                </tr>
            </tbody>
        </table>

        <div class="d-grid gap-2 mx-3">
            <button class="btn btn-primary rounded-pill" type="submit">予約確定</button>
            <a class="btn btn-secondary rounded-pill" href="index.php">戻る</a>
        </div>
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