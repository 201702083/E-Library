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
		$isbn = $_POST['isbn'];
		$_SESSION['rsv'] = $isbn;
		print_r($_POST);
		$query = "select cno,daterented, datedue from ebook where isbn = '$isbn'";
        $stmt = $conn -> prepare($query);
		$stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
		$dr = $row['DATERENTED'];
		$cno = $row['CNO'];
		$user = $_SESSION['id'];
		if( $dr != null ) {
			if ( $cno != $user  ) { // 다른회원이 빌림
						echo  "<script>alert('다른 회원이 대여 중 입니다.');</script>";
						echo "<script>
								if (confirm('예약하시겠습니까?')) {
									location.href='/bookreser.php';
								} else {
									location.href='/books.php';
								}
						</script>";
			}
			else { // 내가 빌림
						echo  "<script>alert('이미 대여 중 입니다.');location.href='books.php';</script>";
			} 

		}
		else { 
				$id = $_SESSION['id'];
				$qr = "select count(*) from ebook where cno = '$id'";
				$st = $conn -> prepare($qr);
				$st -> execute();
				$row = $st -> fetch(PDO::FETCH_ASSOC);
				if($row['COUNT(*)'] >= 3) { // 3권의 row가 있을 경우
					// alert 후 다시 books로
					echo "<script>alert('3권의 책을 대여 중입니다.');  location.href='/books.php'; </script>";
				}
				else { // 3권보다 적은 경우
					$now =  date("Y/m/d");
					$timestamp = strtotime("+11 days");
					$returndate = date("Y/m/d",$timestamp);
					$query2 = "update ebook set daterented ='$now',datedue = '$returndate', cno = '$id' where isbn = '$isbn'";
					$stmt2 = $conn -> prepare($query2);
					$stmt2 -> execute();
					// 책 대여 후 books로
					echo "<script>alert('$now'+' 대여 완료'); location.href='./books.php'; </script>";				
				}

		}
	?>