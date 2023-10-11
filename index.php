<?php
require_once 'function.php';

//DBに接続
$pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';', DB_USER, DB_PASSWORD);
$pdo->query('SET NAMES utf8;');

//セッションデータを取得
$stmt = $pdo->prepare ('SELECT * FROM shop WHERE id = :id');
$stmt->bindValue(':id', 1, PDO::PARAM_INT);
$stmt->execute();
$shop = $stmt->fetch();

$reserve_date_array = array();
for($i = 0; $i <= $shop['reservable_date']; $i++) {
$target_date = strtotime("+{$i} day");

$reserve_date_array[date('Ymd',$target_date)] = date('n/j',$target_date);
}

$reserve_num_array = array();
for($i = 1; $i <= $shop['max_reserve_num']; $i++) {
$reserve_num_array[$i] = $i;
}

$reserve_time_array =array();
for ($i=date('G',strtotime($shop['start_time'])); $i <=date('G',strtotime($shop['end_time'])) ; $i++) { 
    $reserve_time_array[sprintf('%02d',$i).':00']=sprintf('%02d',$i).':00';
} 
// $test = arrayToSelect('name', $reserve_date_array, '1');
session_start();

$err = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POSTパラメータから各種入力値を受け取る
    $reserve_date = $_POST['reserve_date'];
    $reserve_num = $_POST['reserve_num'];
    $reserve_time = $_POST['reserve_time'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $comment = $_POST['comment'];

    // 各種入力値のバリデーション
    if (!$reserve_date) {
        $err['reserve_date'] = '予約日を入力してください。 ';
    }
    if (!$reserve_num) {
        $err['reserve_num'] = '人数を入力してください。 ';
    } else if (!preg_match('/^[0-9]+$/', $reserve_num)) {
        $err['reserve_num'] = '人数を正しく入力してください。';
    }
    if (!$reserve_time) {
        $err['reserve_time'] = '予約時間を入力してください。 ';
    }
    if (!$name) {
        $err['name'] = '氏名を入力してください。';
    } else if (mb_strlen($name, 'utf-8') > 20) {
        $err['name'] = '氏名は20文字以内で入力してください';
    }
    if (!$email) {
        $err['email'] = 'メールアドレスを入力してください。';
    } else if (mb_strlen($email, 'utf-8') > 100) {
        $err['email'] = 'メールアドレスは100文字以内で入力してください';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err['email'] = 'メールアドレスが不正です。 ';
    }
    if (!$tel) {
        $err['tel'] = '電話番号を入力してください。';
    } else if (mb_strlen($tel, 'utf-8') > 20) {
        $err['tel'] = '電話番号は100文字以内で入力してください';
    } else if (!preg_match('/^\d{2,4}-\d{2,4}-\d{3,4}$/', $tel)) {
        $err['tel'] = '電話番号を正しく入力してください。 ';
    }
    if (mb_strlen($comment, 'utf-8') > 2000) {
        $err['comment'] = '備考欄は100文字以内で入力してください';
    }

    // エラーが無ければ次の処理に進む
    if (empty($err)) {
        $stmt = $pdo->prepare("SELECT SUM(reserve_num) FROM reserve WHERE DATE_FORMAT(reserve_date, '%Y%m%d') = :reserve_date AND DATE_FORMAT(reserve_time, '%H:%i') = :reserve_time GROUP BY reserve_date, reserve_time LIMIT 1;");
        $stmt->bindValue(':reserve_date',$reserve_date, PDO::PARAM_STR);
        $stmt->bindValue(':reserve_time',$reserve_time, PDO::PARAM_STR);
        $stmt->execute();
        $reserve_count = $stmt->fetchColumn();
        
        if($reserve_count && ($reserve_count + $reserve_num) > $shop['max_reserve_num']) {
            $err['common'] = 'この日時はすでに予約が埋まっております。';
        }

        if(empty($err)){
            // 各種入力値をセッション変数に保存する
            $_SESSION['RESERVE']['reserve_date'] = $reserve_date;
            $_SESSION['RESERVE']['reserve_num'] = $reserve_num;
            $_SESSION['RESERVE']['reserve_time'] = $reserve_time;
            $_SESSION['RESERVE']['name'] = $name;
            $_SESSION['RESERVE']['email'] = $email;
            $_SESSION['RESERVE']['tel'] = $tel;
            $_SESSION['RESERVE']['comment'] = $comment;

            // 予約確認画面へ遷移する
            header('Location: /reserve/web/confirm.php');
            exit;
        }    
    }
} else {
    // セッションに入力情報がある場合は取得する
    if (isset($_SESSION['RESERVE'])) {
        $reserve_date = $_SESSION['RESERVE']['reserve_date'];
        $reserve_num = $_SESSION['RESERVE']['reserve_num'];
        $reserve_time = $_SESSION['RESERVE']['reserve_time'];
        $name = $_SESSION['RESERVE']['name'];
        $email = $_SESSION['RESERVE']['email'];
        $tel = $_SESSION['RESERVE']['tel'];
        $comment = $_SESSION['RESERVE']['comment'];
    } else {
        $reserve_date = '';
        $reserve_num = '';
        $reserve_time = '';
        $name = '';
        $email = '';
        $tel = '';
        $comment = '';
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
    <title>ご来店展予約</title>
</head>

<body>
    <header>SHOP</header>

    <h1>ご来店展予約</h1>
 
    <form class="m-3" method="post">
        <?php if(isset($err['common'])):?>
            <div class="alert alert-danger" role="alert"><?= $err['common'] ?></div>
        <?php endif; ?>  

        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[1] 予約日を選択</label>
            <?=arrayToSelect('reserve_date', $reserve_date_array, $reserve_date);?>
            <div class="invalid-feedback"><?= $err['reserve_date'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[2] 人数を選択</label>
            <?=arrayToSelect('reserve_num', $reserve_num_array, $reserve_num);?>
            <div class="invalid-feedback"><?= $err['reserve_num'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[3] 予約時間を選択</label>
            <?=arrayToSelect('reserve_time', $reserve_time_array, $reserve_time);?>
            <div class="invalid-feedback"><?= $err['reserve_time'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[4] 予約者情報を入力</label>
            <input type="text" class="form-control <?php if (isset($err['name'])) echo 'is-invalid'?>" name="name" placeholder="氏名" value="<?=$name?>">
            <div class="invalid-feedback"><?= $err['name'] ?></div>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control <?php if (isset($err['email'])) echo 'is-invalid'?>" name="email" placeholder="メールアドレス" value="<?=$email?>">
            <div class="invalid-feedback"><?= $err['email'] ?></div>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control <?php if (isset($err['tel'])) echo 'is-invalid'?>" name="tel" placeholder="電話番号" value="<?=$tel?>">
            <div class="invalid-feedback"><?= $err['tel'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">[5] 備考欄</label>
            <textarea class="form-control <?php if (isset($err['comment'])) echo 'is-invalid'?>" name="comment" rows="3" placeholder="備考欄"><?= $comment?></textarea>
            <div class="invalid-feedback"><?= $err['comment'] ?></div>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary rounded-pill" type="submit">確認画面へ</button>
            <button class="btn btn-secondary rounded-pill" type="button">戻る</button>
        </div>
    <form>
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