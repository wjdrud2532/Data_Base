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

$CurrentDate = date("22/05/05");

                                                                                            //로그인 후 화면, 상영중인 영화의 목록을 출력
//  date("Y-M-D", time());
//  고정 날짜 설정
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}

//로그인이 성공했으므로 아래의 정보를 할당받는다.
$id_val = $_POST["id_val"];
$pw_val = $_POST["pw_val"];
$cid_val = $_POST["cid_val"];
$Email_val = $_POST["Email_val"];
$Bitrh_val = $_POST["Bitrh_val"];


//상영작


?>


<form action="movieview.php" name="login_form1" method="post">
  <input type="hidden" id="CID" name="CID" value= <?php echo $cid_val; ?> ><br><br>
  <meta http-equiv='' content='0;url=movieview.php'>
</form>


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
        if($id_val == "관리자") //만약 관리자로 로그인했을 경우
        {                       //예매내역을 볼 수 있는 mybooklist.php로 이동한다
            ?>

            <meta http-equiv='refresh' content='0;url=mybooklist.php'>

            <?php
        }   

        //사용자의 이름을 출력한다
        print("$id_val 님 환영합니다.");
        ?>

   
     
    </div>


    <!-- 구현하지 않음 -->
    <div class="d-grid d-md-flex justify-content-md-end"  >
     <a href="mylist.php" class="btn btn-primary">마이페이지</a>
     <p>    </p>
     <a href="login.php" class="btn btn-primary">로그아웃</a>
    </div>
    <!--  -->


 
    <div align="center" >
        <!-- 상영작, 상영예정작 버튼을 누를 시 각각의 php로 이동 -->
    <a href="movielist.php" class="btn btn-primary">상영작</a>

    
    <a href="premovielist.php" class="btn" >상영예정작</a>


    <a href="loginout.php" class="btn " style=outline : 5px>상영관</a>
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
              
        <h4 class="display-10">상영중인 영화 목록</h4>

            
            <tr>
                <th>제목</th>
                <th>상영 시작일</th>
                <th>감독</th>
                <th>등급</th>
                <th>상영 시간</th>
                <th>영화 설명</th>
                <th>예매자 수</th>
                <th>누적 관객 수</th>
                <!-- <th></th> -->
                <th>스케줄 보기</th>
            </tr>
        </thead>
        <tbody>
<?php
//MOVIE 테이블에서 출력할 영화의 정보를 가져온다
$stmt = $conn -> prepare("SELECT MID, TITLE, DIRECTOR, OPEN_DAY, LENGTH, RATING, LENGTH, MOVIE_DESCRIP, MOVIE_BOOK, MOVIE_TOTALWATICHING FROM MOVIE WHERE LOWER(TITLE) LIKE '%' || :searchWord || '%' ORDER BY OPEN_DAY");
$stmt -> execute(array($searchWord));



while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?>
    <?php   //$CurrentDate = new DateTime('2022-05-05');
    $MovieOpenDay = $row['OPEN_DAY'];
    $temp = date("22/05/10");
    //$temp2 = date("Y-m-d", date_add(temp(), interval 5 day));
    
    
    
    //현재 날짜 이전의 상영 시작일을 갖고 있는 목록(상영작)만 선택하여 출력한다
    if($MovieOpenDay <= $CurrentDate)
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
                    <?= $row['MOVIE_TOTALWATICHING'] ?>
                </td>
                <!-- <td>
                    <input type="" id="CID" name="CID" value= <?php echo $cid_val; ?> ><br>
                </td> -->
                <td>
                    <!-- 스케줄 보기를 누를 경우 선택한 영화가 갖는 스케줄을 보여주는 movieview.php로 이동한다 -->
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
    <!-- <div class="d-grid d-md-flex justify-content-md-end">
        <a href="movieinput.php?mode=insert" class="btn btn-primary">등록</a>
    </div> -->
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