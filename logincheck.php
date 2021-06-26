<?php
	session_start();

    $adminid = '1';
    $adminpw = 'admin';


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
  $_SESSION['login']='NO';
  $_SESSION['id']=null;
      //<!--php부분 form에 입력한 내용을 데이터베이스와 비교해서 로그인 여부를 알려준다.-->
      if(isset($_POST['userid'])&&isset($_POST['userpw'])){//post방식으로 데이터가 보내졌는지?
        $username=$_POST['userid'];//post방식으로 보낸 데이터를 username이라는 변수에 넣는다.
        $userpw=$_POST['userpw'];//post방식으로 보낸 데이터를 userpw라는 변수에 넣는다.

        if ($username==$adminid && $userpw==$adminpw){ echo "<script>alert('관리자접속');location.href='admin.php';</script>";}
        $query="SELECT COUNT(*) FROM customer where cno='$username' and passwd='$userpw'";
        $stmt = $conn -> prepare($query);
		$stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        if( $row['COUNT(*)'] != 0){//쿼리문을 실행해서 결과가 있으면 로그인 성공
            $_SESSION['login'] ='YES';
            $_SESSION['id'] = $username;
		    echo "<script>alert('로그인 성공!'); </script>";
            	$cno = $_SESSION['id'];



			// 로그인 시 기간이 만료된 책들을 자동 반납한다.
			$now = date("Y/m/d");
			$query = "select count(*) from ebook where datedue < '$now' and cno='$cno'"; // nowtime보다 이전이면 카운트
			$stmt = $conn->prepare($query);
			$stmt -> execute();
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);
			$n = $row['COUNT(*)'];
        
			$query = "select isbn from ebook where datedue<'$now' and cno = '$cno'";
			$stmt = $conn->prepare($query);
			$stmt -> execute();
			while ($row = $stmt -> fetch(PDO::FETCH_ASSOC) ){

				$isbn=$row['ISBN'];
				$q= "select daterented,datedue from ebook where isbn = '$isbn'";
				$stmt = $conn -> prepare($q);
				$stmt -> execute();
				$row = $stmt -> fetch(PDO::FETCH_ASSOC);
				$dr = $row['DATERENTED'];
				$dd = $row['DATEDUE'];

				$query2="insert into previousrental values ('$isbn','$dr','$dd','$cno')";
				$query1="update ebook set cno = null , exttimes = 0 , daterented = null , datedue = null where isbn = '$isbn'";

				$stmt2 = $conn -> prepare($query2);
				$stmt2 -> execute(); // 먼저 대여 기록에 추가

				$stmt1 = $conn -> prepare($query1);
				$stmt1 -> execute(); // 그 후 ebook 상태 초기화
			}
			if ($n > 0) { // 반납한 경우 alert를 띄우고 enter 페이지로
				echo "<script>alert('총 '+$n+'권 자동 반납 처리'); location.href='/enter.php';</script>";
			}
			else{ // 안하면 바로 enter 페이지로
				echo "<script>location.href='/enter.php';</script>";
			};
        }
        else{//쿼리문의 결과가 없으면 로그인 fail을 출력한다
           $_SESSION['login']='NO';
            $_SESSION['id'] = null;
		    echo "<script>alert('로그인 실패'); location.href='./login.php';</script>";
        }
      }
    ?>
