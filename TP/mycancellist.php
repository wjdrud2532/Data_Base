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

                                                                                                //예매 취소된 스케줄들을 출력한다

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
    <h2 class="text-center">예매/취소 내역</h2>   

    <div class="d-grid d-md-flex justify-content-md-end">
        <?php
        // $id_val = $_POST["id_val"];
        // $pw_val = $_POST["pw_val"];
        // $cid_val = $_POST["cid_val"];
        // $Email_val = $_POST["Email_val"];
        // $Bitrh_val = $_POST["Bitrh_val"];


        //관리자만 들어올 수 있으므로 고정
        print("관리자 님 환영합니다.");
        ?>

   
     
    </div>


    <div class="d-grid d-md-flex justify-content-md-end"  >

     <p>    </p>
     <a href="loginout.php" class="btn btn-primary">로그아웃</a>
    </div>

    <div>
      
    </div>
 
    <div align="center" >
        <!-- 각각 클릭시 해당되는 php로 이동 -->
    <a href="mybooklist.php" class="btn ">예매내역</a>

    <a href="mycancellist.php" class="btn btn-primary" >취소내역</a>

    <a href="mywatchlist.php" class="btn" >관람내역</a>

    <a href="customerlist.php" class="btn" >사용자 목록</a>
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
              
        <h4 class="display-10">상영중인 영화 목록</h4>

            
            <tr>
                <th>ID</th>
                <th>DATE</th>
                <th>SEATS</th>
                <th>STATUS</th>
                <th>CID</th>
                <th>SID</th>
                <!-- <th>예매 취소</th> -->
            </tr>
        </thead>
        <tbody>
<?php
$stmt = $conn -> prepare("SELECT ID, RC_DATE, SEATS, STATUS, CID, SID, CUSTOMER_ID FROM TICKETING WHERE LOWER(ID) LIKE '%' || :searchWord || '%'  ORDER BY RC_DATE");
$stmt -> execute(array($searchWord));
//TICKETING 테이블에서 정보를 가져온다
//ORDER BY를 통해 날짜를 내림차순 정렬한다


while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?>
    <?php   //$CurrentDate = new DateTime('2022-05-05');
    $MovieOpenDay = $row['RC_DATE'];
    $temp = date("22/05/10");
    $IsBooked = $row['STATUS'];
    //$temp2 = date("Y-m-d", date_add(temp(), interval 5 day));
    
    
    
    //예약 취소된 상태를 가진 것들만출력
    if($IsBooked == 'C')
    {
        ?>
            <tr>
                <td>
                    <?= $row['ID'] ?></a>
                </td>
                <td>
                    <?= $MovieOpenDay ?>
                </td>
                <td>
                    <?= $row['SEATS'] ?>
                </td>
                <td>
                    <?= $row['STATUS'] ?>
                </td>
                <td>
                    <?= $row['CID'] ?>
                </td>
                <td>
                    <?= $row['SID'] ?>
                </td>
                <!-- <td>
                <div class="modal-footer">
                <form action="movieprocess.php?mode=modify" method="post" class="row">
                    <input type="hidden" name="bookId" value="<?= $bookId ?>">
                    <button type="submit" class="btn btn-danger">예매취소</button>
                </form>
            </div>
                </td> -->
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