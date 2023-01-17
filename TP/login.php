<?php
$bookId = $_GET['bookId'] ?? '';
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
    $stmt = $conn->prepare("SELECT BOOK_NAME, PUBLISHER, PRICE FROM BOOK WHERE BOOK_ID = :bookId ");
    $stmt->execute(array($bookId));
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookName = $row['BOOK_NAME'];
        $publisher = $row['PUBLISHER'];
        $price = $row['PRICE'];
    }
}
?>

<!DOCTYPE html>


<!-- html에서 입력 받은 정보를 login_ok.php로 전송한다 -->
<meta charset="utf-8" />
<form method='post' action='login_ok.php'>
<table>
<tr>
	<td>아이디</td>
	<td><input type='text' name='user_id' tabindex='1'/></td>
	<td rowspan='2'><input type='submit' tabindex='3' value='로그인' style='height:50px'/></td>
</tr>
<tr>
	<td>비밀번호</td>
	<td><input type='password' name='user_pw' tabindex='2'/></td>
</tr>
</table>
</form>
