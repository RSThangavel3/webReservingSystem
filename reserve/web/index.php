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
 
    <form class="m-3" method="post" action="confirm.php">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[1] 予約日を選択</label>
            <select class="form-select" aria-label="Default select example">
                <option selected>日付</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[2] 人数を選択</label>
            <select class="form-select" aria-label="Default select example">
                <option selected>人数</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[3] 予約時間を選択</label>
            <select class="form-select" aria-label="Default select example">
                <option selected>時間</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">[4] 予約者情報を入力</label>
            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="氏名">
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="メールアドレス">
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="電話番号">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">[5] 備考欄</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="備考欄"></textarea>
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