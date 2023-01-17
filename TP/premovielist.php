<?php
$tns = "
	(DESCRIPTION=
		(ADDRESS_LIST=
			(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521))
		)
		(CONNECT_DATA=
			(SERVICE_NAME=XE)
		)
	)
";
$url = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201702086';
$password = 'awhe';
$searchWord = $_GET['searchWord'] ?? '';


                                                                                            //movielist.php와 거의 같음, 상영 예정인 영화 목록 출력
//출력되는 날짜와 누적 관객수만 다름

$CurrentDate = date("22/05/05");
//  date("Y-M-D", time());
//  고정 날짜 설정
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}

//상영작
?>


<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
    <meta charset="utf-8">
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
    <title>TP 한정경</title>
</head>
<body>

<div class="container">
    <h2 class="text-center">영화목록</h2>   

    <div class="d-grid d-md-flex justify-content-md-end">
        <?php
        $id_val = $_POST["id_val"];
        $pw_val = $_POST["pw_val"];
        $cid_val = $_POST["cid_val"];
        $Email_val = $_POST["Email_val"];
        $Bitrh_val = $_POST["Bitrh_val"];

        print("$id_val 님 환영합니다.");
        ?>

   
     
    </div>


    <div class="d-grid d-md-flex justify-content-md-end"  >
     <a href="movieinput.php?mode=insert" class="btn btn-primary">마이페이지</a>
     <p>    </p>
     <a href="login.php" class="btn btn-primary">로그아웃</a>
    </div>

    <div>
      
    </div>
 
    <div align="center" >
    <a href="movielist.php" class="btn ">상영작</a>

    
    <a href="premovielist.php" class="btn btn-primary" >상영예정작</a>


    <a href="loginout.php" class="btn " style=outline : 5px>상영관</a>
    </div>

    <div>
       
    </div>
        
    <form class="row">
        <div class="col-10">
            <label for="searchWord" class="visually-hidden">Search Word</label>
            <input type="text" class="form-control" id="searchWord" name="searchWord" placeholder="검색어 입력" value="<?= $searchWord ?>">
        </div>
        <div class="col-auto text-end">
            <button type="submit" class="btn btn-primary mb-3">검색</button>
        </div>
    </form>

    
    <table class="table table-bordered text-center">
        <thead>
              
        <h4 class="display-10">상영예정 영화 목록</h4>

            
            <tr>
                <th>제목</th>
                <th>상영 시작일</th>
                <th>감독</th>
                <th>등급</th>
                <th>상영 시간</th>
                <th>영화 설명</th>
                <th>예매자 수</th>
                <th>스케줄 보기</th>
            </tr>
        </thead>
        <tbody>
<?php
$stmt = $conn -> prepare("SELECT MID, TITLE, DIRECTOR, OPEN_DAY, LENGTH, RATING, LENGTH, MOVIE_DESCRIP, MOVIE_BOOK, MOVIE_TOTALWATICHING FROM MOVIE WHERE LOWER(TITLE) LIKE '%' || :searchWord || '%' ORDER BY OPEN_DAY");
$stmt -> execute(array($searchWord));



while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?>
    <?php   //$CurrentDate = new DateTime('2022-05-05');
    $MovieOpenDay = $row['OPEN_DAY'];
    $temp = date("22/05/10");
    //$temp2 = date("Y-m-d", date_add(temp(), interval 5 day));
    
    
    
    //현재 날짜 이후의 상영 시작일을 갖고 있는 목록(상영작)만을 출력한다
    if($MovieOpenDay > $CurrentDate)
    {
        ?>
            <tr>
                <td>
                    <?= $row['TITLE'] ?></a>
                </td>
                <td>
                    <?= $MovieOpenDay ?>
                </td>
                <td>
                    <?= $row['DIRECTOR'] ?>
                </td>
                <td>
                    <?= $row['RATING'] ?>
                </td>
                <td>
                    <?= $row['LENGTH'] ?>
                </td>
                <td>
                    <?= $row['MOVIE_DESCRIP'] ?>
                </td>
                <td>
                    <?= $row['MOVIE_BOOK'] ?>
                </td>
                <td>
                    <a href="movieview.php?Movie_Id=<?= $row['MID'] ?>">스케줄 보기</a>
                    <?php //MID를 Movie_ID란 이름으로 movieview.php에 보냄  ?>
                </td>
            </tr>
        <?php
    }
    ?>

<?php
}
?>
        </tbody>
    </table>

</div>
</body>
</html>

<?php
function SendPost($SendPhpAddress)
{

    echo "<script>alert('정상동작중.');history.back();</script>";

$user_id = $cid_val;
$user_pw = $pw_val;

$searchWord = $_GET['searchWord'] ?? '';
$stmt3 = $conn -> prepare("SELECT CID, NAME, PASSWORD, EMAIL, BIRTH_DATE, SEX FROM CUSTOMER_TP WHERE LOWER(CID) LIKE '%' || :searchWord || '%' ORDER BY CID");
$stmt3 -> execute(array($searchWord));

$istrue = false;
        

while ($row = $stmt3 -> fetch(PDO::FETCH_ASSOC))
{
        
        $Cid = $row['CID'];
        $Password = $row['PASSWORD'];

        if($user_id == $Cid && $user_pw == $Password)
        {
        $Name = $row['NAME'];
        $Email = $row['EMAIL'];
        $Birth = $row['BIRTH_DATE'];
        ?>

        
<html>
<body>
<form action=<?php echo $SendPhpAddress; ?>s name="login_form1" method="post">

  <input type="text" id="id_val" name="id_val" value= <?php echo $Name; ?> ><br>
  <input type="text" id="pw_val" name="pw_val" value= <?php echo $Password; ?> ><br><br>
  <input type="text" id="cid_val" name="cid_val" value= <?php echo $Cid; ?> ><br><br>
  <input type="text" id="Email_val" name="Email_val" value= <?php echo $Email; ?> ><br><br>
  <input type="text" id="Bitrh_val" name="Bitrh_val" value= <?php echo $Birth; ?> ><br><br>

  <meta http-equiv='refresh' content='0;url=<?php echo $SendPhpAddress; ?>'>
</form>

<script type="text/javascript">
    document.login_form1.submit();
</script>
</body>
</html>
        <?php
        break;
      }
        
}
}
?>