<?php
	session_start();
	$tns="
		(DESCRIPTION = 
			(ADDRESS_LIST = (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
			(CONNECT_DATA = (SERVICE_NAME=XE))
		)
	";
	$dsn = "oci:dbname=".$tns.";charset=utf8";
	$username = 'c##tp';
	$password = '1234';
	$searchword = $_GET['searchword'] ?? '';
	try{
		$conn = new PDO($dsn,$username,$password);
	}catch(PDOException $e){
		echo(" ! 에러 ".$e -> getMessage());
	}
	  header("Content-Type: text/html; charset=utf-8");

	  // 세션값을 초기화한다.
	  $_SESSION['login']='NO';
	  $_SESSION['id']=null;  
	  // alert 하고 login창으로
	  echo "<script>alert('로그아웃되었습니다.');location.href='./login.php';</script>";
    ?>