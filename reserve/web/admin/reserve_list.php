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
    <div class="row m-3">
        <div class="col">
            <select class="form-select" aria-label="Default select example">
                <option selected>2023年</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="col">
            <select class="form-select" aria-label="Default select example">
                <option selected>1月</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
    </div>
    <table class="table">
        <tbody>
            <tr>
                <td>1/1(土)</td>
                <td>12:00</td>
                <td>テスト 4名<br>
                    XXX@XXX.XXX<br>
                    111-111-1111<br>
                    comming for friends</td>
            </tr>
            <tr>
                <td>1/1(土)</td>
                <td>12:00</td>
                <td>テスト 4名<br>
                    XXX@XXX.XXX<br>
                    111-111-1111<br>
                    comming for friends</td>
            </tr>
            <tr>
                <td>1/1(土)</td>
                <td>12:00</td>
                <td>テスト 4名<br>
                    XXX@XXX.XXX<br>
                    111-111-1111<br>
                    comming for friends</td>
            </tr>
            <tr>
                <td>1/1(土)</td>
                <td>12:00</td>
                <td>テスト 4名<br>
                    XXX@XXX.XXX<br>
                    111-111-1111<br>
                    comming for friends</td>
            </tr>
            <tr>
                <td>1/1(土)</td>
                <td>12:00</td>
                <td>テスト 4名<br>
                    XXX@XXX.XXX<br>
                    111-111-1111<br>
                    comming for friends</td>
            </tr>
        </tbody>
    </table>
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