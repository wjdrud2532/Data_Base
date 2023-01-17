<?php
$tns = "
    (DESCRIPTION=
        (ADDRESS_LIST=	(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
        (CONNECT_DATA=	(SERVICE_NAME=XE))
    )
";
$url = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201702086';
$password = 'awhe';
$MIDFromList = $_GET['Movie_Id'] ?? '';
$searchWord = $_GET['searchWord'] ?? '';
$CurrentDate = date("22/05/05");
//MID를 Movie_ID란 이름으로 movelist.php에서 받은 Movie.php


                                                                                                    //어떤 영화의 모든 스케줄을 출력

$MovieList = $_GET['MovieList'] ?? '';
$mode = $_GET['mode'] ?? '';
$mode = "insert";



try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}

//MOVIE 테이블에서 정보를 가져온다
$stmt = $conn -> prepare("SELECT MID, TITLE, DIRECTOR, OPEN_DAY, LENGTH, RATING, LENGTH FROM MOVIE WHERE LOWER(TITLE) LIKE '%' || :searchWord || '%' ORDER BY TITLE");
$stmt -> execute(array($MovieList));

$stmt4 = $conn -> prepare("SELECT MID, NAME FROM ACTOR WHERE LOWER(MID) LIKE '%' || :searchWord || '%' ORDER BY MID");
$stmt4 -> execute(array($MovieList));

$actor = '';

$MovieName = '';
$Director = '';
$Open_Day = '';
$Length = '';
$Rating = '';
$MID = '';
$cid_val = '';


while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
//movelist.php 에서 받은 Movie_ID와 같은 MID를 가진 튜플을 찾아 저장 후 중단
    if( ($MMID = $row['MID']) == $MIDFromList)
    {
        $MovieName = $row['TITLE'];
        $Open_Day = $row['OPEN_DAY'];
        $Director = $row['DIRECTOR'];
        $Length = $row['LENGTH'];
        $Rating = $row['RATING'];
        break;
    }
}
    
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <style>
        a {
            text-decoration: none;
        }
    </style>
    <title>영화상세정보</title>

</head>
<body>
<div class="container">
    <h2 class="display-6">영화상세정보</h2>
    <?php //전달받은 ID와 같은 영화 정보를 출력  ?>
    <table class="table table-bordered text-center">
        <tbody>
            <tr>
                <td>제목</td>
                <td><?= $MovieName ?></td>
            </tr>
            <tr>
                <td>상영 시작일</td>
                <td><?= $Open_Day ?></td>
            </tr>
            <tr>
                <td>감독</td>
                <td><?= $Director ?></td>
            </tr>
            <tr>
                <td>등급</td>
                <td><?= $Rating ?></td>
            </tr>
            <tr>
                <td>상영 시간</td>
                <td><?= $Length ?></td>
            </tr>
            <tr>
                <td>영화 번호</td>
                <td><?= $MIDFromList ?></td>
            </tr>

            <tr>
            <?php while ($row4 = $stmt4 -> fetch(PDO::FETCH_ASSOC)  )
            {
                if( $row4['MID'] == $MMID)
                {
                    $actor = $row4['NAME'];
                    ?>

                <tr>
                    <td>출연 배우</td>
                    <td><?= $actor ?></td>
                </tr>

                    <?php
                }
            }

            ?>
            </tr>
        </tbody>
    </table>


    <h2 class="display-6">영화 스케줄</h2>
   

    <?php

$DateTime = '';
$Tname = '';
$SID = '';
$S_MID = '';

//SCHEDULE_TP 테이블에서 전달 받은 MID와 같은 번호를 가진 튜플을 while문을 통해 전부 출력한다
$stmt2 = $conn -> prepare("SELECT SDATETIME, TNAME, MID, SID  FROM SCHEDULE_TP WHERE LOWER(SDATETIME) LIKE '%' || :MID || '%' ORDER BY SID");
$stmt2 -> execute(array($MID));


while ($row2 = $stmt2 -> fetch(PDO::FETCH_ASSOC)  ) //&& $S_MID == $MMID
{
   ?> 
   <?php

    //같은 ID값을 가질 경우 스케줄에 대한 정보를 출력한다
    if($row2['MID'] == $MIDFromList)
    {
        $stmt3 = $conn -> prepare("SELECT TNAME, SEATS FROM THEATER WHERE SEATS LIKE '%' || :searchWord || '%' ORDER BY TNAME");
        $stmt3 -> execute(array($searchWord));
        
        
        while ($row3 = $stmt3 -> fetch(PDO::FETCH_ASSOC)  )
            //스케줄에 기록된 상영관 이름과 같은 이름을 가진 상영관의 좌석 정보를 가져온다
            if($row3['TNAME'] == $row2['TNAME'])
            {
                $Tname = $row3['TNAME'];
                $Seats = $row3['SEATS'];
            }
            $Sdate = $row2['SDATETIME'];
            $Sid = $row2['SID'];
            

        ?>
        
        <table class="table table-bordered text-center">
            <tbody>
                <tr>
                    <!-- 예매할 때 사용하기 위해 아래의 정보를 movieprocess.php로 전송한다 -->
                    <form class="row g-3 needs-validation" method="post" action="movieprocess.php?mode=<?= $mode ?>" novalidate>
                        <td type="text" >상영일 : <?= $Sdate ?></td>
                        <input type="hidden" id="SDATETIME" name="SDATETIME" value= <?php echo $Sdate; ?> ><br>

                        <td type="text" >상영관 : <?= $Tname ?></td>
                        <input type="hidden" id="TNAME" name="TNAME" value= <?php echo $Tname; ?> ><br>

                        <td type="text" >예매 가능 좌석 : <?= $Seats ?></td>
                        <input type="hidden"  value= <?php echo $Seats; ?> ><br>

                        <td type="text" >스케줄번호 : <?= $Sid ?></td>
                        <input type="hidden" id="SID" name="SID" value= <?php echo $Sid; ?> ><br>

                        <td type="text" >MID : <?= $MIDFromList ?></td>
                        <input type="hidden" id="MID" name="MID" value= <?php echo $MIDFromList; ?> ><br>

                        <td>
                                                                 <!-- 최소1, 최대 10까지 입력 가능 -->
                        <input type="number" id="SEATS" name="SEATS" value=""   min="1" max="10" ><br>
                        
                        <input type="hidden" id="SID" name="SID" value= <?php echo $MID; ?> ><br>
                        <button class="btn btn-primary" type="submit"><?= $mode == 'insert' ? '예매하기' : '수정'?></button>
                        </td>
                      
                    </from>
                </tr>
            
            </tbody>
        </table>
        <?php       
    }
    //
    // $to = "wjdrud2532@naver.com";
    // $subject = "제목";
    // $message = "메일 내용";
    // $from = "wjdrud2532@naver.com";
    // $headers = "From: $from\r\n";
    // $headers .= "Cc: $from\r\n";
    // mail($to, $subject, $message, $headers);
    // echo "Mail Sent";

   ?>

    

<?php
}
?>



<?php

?>
    <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="movielist.php" class="btn btn-success">목록</a>
        <a href="movieinput.php?MID=<?= $MID ?>&mode=modify" class="btn btn-warning">수정</a>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">삭제</button>
    </div>
</div> -->
<!-- Delete Confirm Modal -->
<!-- <div class="modal fade" id="deleteConfirmModal" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel"><?= $MovieName ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                위의 책을 삭제하시겠습니까?
            </div>
            <div class="modal-footer">
                <form action="movieprocess.php?mode=delete" method="post" class="row">
                    <movieinput type="hidden" name="MID" value="<?= $MID ?>">
                    <button type="submit" class="btn btn-danger">삭제</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
            </div>
        </div>
    </div>
</div> -->
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</html>