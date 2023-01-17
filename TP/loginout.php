<?php
//echo "<script>alert('로그아웃 되었습니다.');history.back();</script>";
session_start();
session_destroy();

?>
<meta http-equiv='refresh' content='0;url=http://localhost/TP/prc/login.php'>
