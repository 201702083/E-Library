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
?>
<?php
  header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1">
	<title>MY PAGE</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <body style="background-color: antiquewhite;">
        <h1 class="title" style="margin: 0; padding: 0;">
            Ebook Library
        </h1>
            <button type="button" style= "position:fixed; top : 10px; left : 10px;" onclick="location.href='enter.php'" >Back</button>

        <div class="userinfo">
            <?php 
                // 내 정보 출력
                  $cno = $_SESSION['id'];
                  $query = "select * from customer where cno='$cno'";
                    $stmt = $conn -> prepare($query);
		            $stmt -> execute();
                    $row = $stmt -> fetch(PDO::FETCH_ASSOC);
            ?>
            <h3>Information</h3>
            <li><? echo $row['NAME']?></li>
            <li><?php if(!isset($row['EMAIL'])){echo "미등록";}else{ echo $row['EMAIL'];}?></li>
        </div>
        <div style="height: 130px;">
        </div>
        <div class="back" style="width: auto; margin: 0px 50px;">
            <h1>활동</h1>
            <div style="background-color:white;">
            <li><button type = "button" class="mypageToggle" onclick="location.href='myrent.php'"> 대여 중</button></li><br>
            <li><button type = "button" class="mypageToggle" onclick="location.href='myreser.php'"> 예약 중</button></li><br>
            <li><button type = "button" class="mypageToggle" onclick="location.href='myhistory.php'"> 대여 기록</button></li><br>
            </div>
            <h1>정보 수정</h1>
            <li><button type = "button" class="mypageToggle" onclick="location.href='changepw.php'"> 비밀번호 변경 </button></li><br>
            <li><button type = "button" class="mypageToggle" onclick="location.href='changemail.php'"> 이메일 변경 </button></li><br>
        </div>
    </body>
</html>
