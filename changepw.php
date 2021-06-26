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
	<title>PASSWD SET</title>
    <link rel="stylesheet" href="./style.css">
    <?php
      if(isset($_POST['oldpw'])&&isset($_POST['newpw'])){//post방식으로 데이터가 보내졌는지?
        $username=$_SESSION['id'];// 세션으로 가져온 cno
        $oldpw=$_POST['oldpw'];// 기존 패스워드
        $newpw=$_POST['newpw'];// 새로운 패스워드
        $query="SELECT COUNT(*) FROM customer where cno='$username' and passwd='$oldpw'";
        $stmt = $conn -> prepare($query);
		$stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        if( $row['COUNT(*)'] != 0){ // id / pw 가 일치하는 것이 있다면 변경 진행
            $query="update customer set passwd='$newpw' where cno = '$username'";
            $stmt = $conn -> prepare($query);
		    $stmt -> execute();
            // 변경 후 alert , 다시 mypage로
		    echo "<script>alert('변경 성공!'); location.href='/mypage.php';</script>";
        }
        else{//쿼리문의 결과가 없으면 변경 fail을 출력한다

		    echo "<script>alert('실패 : 현재 비밀번호  틀림');</script>";
        }
      }
    ?>
    </head>
    <body style="background-color: antiquewhite;">
        <h1 class="title" style="margin: 0; padding: 0;">
            Ebook Library
        </h1>
            <button type="button" style= "position:fixed; top : 10px; left : 10px;" onclick="location.href='mypage.php'" >Back</button>

        <div class="userinfo">
            <?php 
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
            <h1>패스워드 변경</h1>
            <div style="background-color:white;">
        <form method="post" action="changepw.php" >
             <div class="idpw">
                <p>
                    현재 비밀번호 <input type="text" name="oldpw" class="inph">
                </p>
                <p>
                    바꿀 비밀번호 <input type="password" name="newpw" class="inph">
                </p>
            </div>
                <p>
                     <button type="submit" id="btn" >
                        변경
                    </button>
                    </form >
                </p>

        </div>
    </body>
</html>
