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
//  date("Y-M-D", time());
//  고정 날짜 설정
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}


if(!isset($_POST['user_id']) || !isset($_POST['user_pw'])) exit;
$user_id = $_POST['user_id'];
$user_pw = $_POST['user_pw'];

//CUSTOMER_TP 테이블의 정보와 입력된 정보가 같은지 비교
$searchWord = $_GET['searchWord'] ?? '';
$stmt = $conn -> prepare("SELECT CID, NAME, PASSWORD, EMAIL, BIRTH_DATE, SEX FROM CUSTOMER_TP WHERE LOWER(CID) LIKE '%' || :searchWord || '%' ORDER BY CID");
$stmt -> execute(array($searchWord));

$istrue = false;
        

while ($row = $stmt -> fetch(PDO::FETCH_ASSOC))
{
        
        $Cid = $row['CID'];
        $Password = $row['PASSWORD'];

        
        // ID PW 모두 같다면
        if($user_id == $Cid && $user_pw == $Password)
        {
        $Name = $row['NAME'];
        $Email = $row['EMAIL'];
        $Birth = $row['BIRTH_DATE'];
        //나머지 정보 모두 할당한 뒤 아래 html에서 POST 형식으로 movielist.php로 보낸다
                ?>

        <tr>
                
                <td>
                    <?= $Cid ?>
                </td>
              
                <td>
                    <?= $Password ?>
                </td>
        </tr>
            <?php
                
                $istrue = true;
        }
      if($istrue)
      {
        //echo "로그인 성공";
        ?>

        
<html>
<body>
<form action="movielist.php" name="login_form1" method="post">

  <input type="text" id="id_val" name="id_val" value= <?php echo $Name; ?> ><br>
  <input type="text" id="pw_val" name="pw_val" value= <?php echo $Password; ?> ><br><br>
  <input type="text" id="cid_val" name="cid_val" value= <?php echo $Cid; ?> ><br><br>
  <input type="text" id="Email_val" name="Email_val" value= <?php echo $Email; ?> ><br><br>
  <input type="text" id="Bitrh_val" name="Bitrh_val" value= <?php echo $Birth; ?> ><br><br>

  <meta http-equiv='refresh' content='0;url=movielist.php'>
</form>

<script type="text/javascript">
    document.login_form1.submit();
</script>
</body>
</html>


        <!-- <meta http-equiv='refresh' content='0;url=movielist.php'> -->
        <?php
        break;
      }
        
}
if($istrue == false)
{
        echo "로그인 실패";
        echo "<script>alert('아이디 또는 패스워드가 잘못되었습니다.');history.back();</script>";
        exit;
        
}
