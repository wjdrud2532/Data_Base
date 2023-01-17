<?php
$tns = "
    (DESCRIPTION=
        (ADDRESS_LIST=	(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
        (CONNECT_DATA=	(SERVICE_NAME=XE))
    )
";
$dsn = "oci:dbname=".$tns.";charset=utf8";

                                                                                                            //예매, 취소 등의 작업을 함


//sql 테이블 접근을 위한 로그인 정보
$username = 'd201702086';
$password = 'awhe';

//현재 날짜 지정
$CurrentDate = date("22/05/05");

$dbh = new PDO($dsn, $username, $password);
switch($_GET['mode']){
    case 'insert':

        //insert로 들어온 경우 예매 작업을 수행한다

        $stmt = $dbh->prepare("INSERT INTO TICKETING (ID, RC_DATE, SEATS, STATUS, CID, SID, CUSTOMER_ID) VALUES ( :ID, :RC_DATE, :SEATS, :STATUS, :CID, :SID, :CUSTOMER_ID)");
        $stmt->bindParam(':ID',$ID);
        $stmt->bindParam(':RC_DATE',$RC_DATE);
        $stmt->bindParam(':SEATS',$SEATS);
        $stmt->bindParam(':STATUS',$STATUS);
        $stmt->bindParam(':CID',$CID);
        $stmt->bindParam(':SID',$SID);
        $stmt->bindParam(':CUSTOMER_ID',$CUSTOMER_ID);
        

        //외래키가 들어오지 않아 스케줄 번호를 특정할 수 없고 때문에
        //while의 마지막 값이 들어온다
        
       
        $t2 = $_POST['SDATETIME'];;
        $t3 = $_POST['SEATS'];;

        echo $t3;

        //랜덤 ID 값 생성
        $temp = rand(1, 20);
        $ID = $temp;

        $RC_DATE = $t2;
        $SEATS = $t3;

        //이때 예약된 스케줄이 현재 날짜보다 이전인 경우 관람한 것으로 가정
        if($t2 <= $CurrentDate)
        {
            $STATUS = 'W';      //관람한 상태로 설정
        }
        else
        {
            $STATUS = 'R';      //예약 상태로 설정
        }
        
        $CID = null;//$_POST['MID'];
        $SID = null;//$_POST['SID'];
        $CUSTOMER_ID = null;

        $stmt->execute();
        header("Location: movielist.php");
        break;


    case 'modify':

        //수정일 경우 -> 예매 취소에 사용한다

        $stmt = $dbh->prepare('UPDATE TICKETING SET STATUS = :BookStatus WHERE ID = :bookId');
        $stmt->bindParam(':bookId', $bookId);
        $stmt->bindParam(':BookStatus', $movieName);

        //POST 값으로 ID가 오면 TICKETING에서 같은 ID를 가진 튜플의 STATUS를 C(취소함)으로 만든다

        $movieName = 'C';
        $bookId = $_POST['bookId'];

        
        echo $bookId;
        echo $movieName;
        
        
        $stmt->execute();
        header("Location: mybooklist.php?bookId=$bookId");
        break;




        //사용하지 않음
    case 'delete':
        $stmt = $dbh->prepare('DELETE FROM TICKETING WHERE ID = :bookId ');
        $stmt->bindParam(':bookId', $bookId);

        //echo "afawf";
        
        echo $bookId;
        $bookId = 'C';
        $stmt->execute();
        header("Location: mybooklist.php");
        break;
}
?>