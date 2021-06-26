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
	// oracle 접속

  header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1">
	<title>ENTER LIBRARY</title>
</head>

    <body>
        <h1 class="title" style="font-size: 100px;padding-top:0px;padding-bottom: 0px;">
            CNU E-book Library
        </h1>
            <button type="button" style= "position:fixed; top : 10px; left : 10px;" onclick="location.href='logout.php'" >LogOut</button>

		<div class="twoway">
                <button type="button" class="way" style="background-color:white;box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);font-size:40px;width:350px; height:140px;" onclick="location.href='main.php'">전자도서관 입장</button>
				
                <button type="button" class="way" style="background-color:white;box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);font-size:40px;width:350px; height:140px;" onclick="location.href='mypage.php'">마이 페이지</button>
        </div>
    </body>

</html>
