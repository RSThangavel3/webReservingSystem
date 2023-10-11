<?php
require_once(dirname(__FILE__) . '/../function.php');

//DBに接続
$pdo = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';', DB_USER, DB_PASSWORD);
$pdo->query('SET NAMES utf8;');

$year = @$_GET['year'];
$month = @$_GET['month'];

if(!$year) {
    $year = date('Y');
}

if(!$month) {
    $month = date('m');
}

$stmt = $pdo->prepare("SELECT * FROM reserve WHERE DATE_FORMAT(reserve_date, '%Y%m') = :yyyymm ORDER BY reserve_date, reserve_time");
$stmt->bindValue(':yyyymm',$year.$month, PDO::PARAM_STR);
$stmt->execute();
$reserve_list = $stmt->fetchAll();

$year_array = array();
$current_year = date('Y');
for($i = ($current_year -1); $i <= ($current_year +3); $i++) {
    $year_array[$i] = $i.'年';
}

$month_array = array();
for($i = 1; $i <= 12; $i++){
    $month_array[sprintf('%02d',$i)] = $i.'月';
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
    <title>予約リスト</title>
</head>

<body>
    <header class="navbar">
        <div class="container-fluid">
            <div class="navbar-brand">SHOP</div>
            <form class="d-flex">
                <a href="../admin/reserve_list.php" class="mx-3"><i class="bi bi-list-task nav-icon"></i></a>
                <a href="../admin/setting.php"><i class="bi bi-gear nav-icon"></i></a>
            </form>
        </div>
    </header>
    <h1>予約リスト</h1>

    <form id="filter-form" method="get">
        <div class="row m-3">
            <div class="col">
                <?=arrayToSelect('year', $year_array, $year);?>
            </div>
            <div class="col">
                <?=arrayToSelect('month', $month_array, $month);?>
            </div>
        </div>
    <form>
        
    <?php if(!$reserve_list) :?>
        <div class="alert alert-warning" role="alert">予約データがありません。</div>
    <?php else: ?>    
    <table class="table">
        <tbody>
            <?php foreach ($reserve_list as $reserve): ?>
                <?php
                if (!empty($reserve['comment'])) {
                    $comment = mb_strimwidth($reserve['comment'], 0, 90, '....');
                } else {
                    $comment = '';
                }
                ?>    
            <tr>
                <td><?= format_date($reserve['reserve_date'])?></td>
                <td><?= format_time($reserve['reserve_time']) ?></td>
                <td><?= $reserve['name'] ?>　<?= $reserve['reserve_num'] ?>名<br>
                    <?= $reserve['email'] ?><br>
                    <?= $reserve['tel'] ?><br>
                    <?= $comment ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
    $('.form-select').change(function(){
        $('#filter-form').submit();
    })
    </script>   
</body>

</html>