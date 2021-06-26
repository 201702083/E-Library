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
<?php 
	$isbn = $_SESSION['rsv'];
	$username = $_SESSION['id'];
	$now =  date("Y/m/d");
//	echo "$isbn";
	$query = "insert into reservation values ('$isbn', '$username', '$now')";
    // 예약 테이블에 추가한다.
	$stmt = $conn -> prepare($query);
	$stmt -> execute();
	// alert 하고 다시 books로
	echo "<script>alert('$now'+' '+$isbn+' 예약 완료'); location.href='./books.php'; </script>";				

	?>