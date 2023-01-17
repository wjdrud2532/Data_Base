<?php
$bookId = $_GET['movieId'] ?? '';
$mode = $_GET['mode'] ?? '';

$bookName = '';
$publisher = '';
$price = '';
if ($mode == 'modify') {
    $tns = "
        (DESCRIPTION=
            (ADDRESS_LIST=	(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
            (CONNECT_DATA=	(SERVICE_NAME=XE))
        )
    ";
    $url = "oci:dbname=" . $tns . ";charset=utf8";
    $username = 'd201702086';
    $password = 'awhe';
    try {
        $conn = new PDO($url, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: " . $e->getMessage());
    }
    $stmt = $conn->prepare("SELECT TITLE, OPEN_DAY, DIRECTOR FROM MOVIE WHERE MID = :Movie_ID ");
    $stmt->execute(array($bookId));
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookName = $row['TITLE'];
        $publisher = $row['OPEN_DAY'];
        $price = $row['DIRECTOR'];
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
          crossorigin="anonymous">
    <style>
        a {
            text-decoration: none;
        }
    </style>
    <title>Book moviemoviemovieinput</title>
</head>
<body>
<div class="container mb-3">
    <h2 class="display-4"><?= $mode == 'insert' ? '도서등록' : '도서정보수정'?></h2>
    <form class="row g-3 needs-validation" method="post" action="movieprocess.php?mode=<?= $mode ?>" novalidate>
        <div class="form-floating mb-3">
            <moviemoviemovieinput type="text" class="form-control" maxlength="13" id="bookName" name="bookName" placeholder="제목" value="<?= $bookName ?>" required>
            <label for="bookName" class="form-label">제목</label>
            <div class="invalid-tooltip">
                제목을 입력하세요.
            </div>
        </div>
        <div class="form-floating mb-3">
            <moviemoviemovieinput type="text" class="form-control" maxlength="13" id="publisher" name="publisher" placeholder="출판사" value="<?= $publisher ?>" required>
            <label for="publisher" class="form-label">출판사</label>
            <div class="invalid-tooltip">
                출판사를 입력하세요.
            </div>
        </div>
        <div class="form-floating mb-3">
            <moviemoviemovieinput type="number" class="form-control" id="price" name="price" placeholder="가격" value="<?= $price ?>" required>
            <label for="price">가격</label>
            <div class="invalid-tooltip">
                올바른 도서가격을 입력하세요.
            </div>
        </div>
        <div class="mb-3">
            <moviemoviemovieinput type="hidden" name="bookId" value="<?= $bookId ?>">
            <button class="btn btn-primary" type="submit"><?= $mode == 'insert' ? '등록' : '수정'?></button>
        </div>
    </form>
<?php

?>