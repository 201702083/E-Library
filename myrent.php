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
	<title>MY RENT</title>
    <link rel="stylesheet" href="./style.css">
    <?php
      if(isset($_POST['isbn']) ){ //반납 post
        $username=$_SESSION['id'];
        $isbn=$_POST['isbn'];
		$q= "select daterented from ebook where isbn = '$isbn'";
		$stmt = $conn -> prepare($q);
		$stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
		$dr = $row['DATERENTED'];
		$now =  date("Y/m/d",time()); // 오늘 날짜를 구함

		$query1="update ebook set cno = null , exttimes = 0 , daterented = null , datedue = null where isbn = '$isbn'";
		$query2="insert into previousrental values ('$isbn','$dr','$now','$username')"; 
		                                         // 오늘 날짜로 반납일을 처리
        $stmt2 = $conn -> prepare($query2);
		$stmt2 -> execute();

        $stmt1 = $conn -> prepare($query1);
		$stmt1 -> execute();
		echo "<script>alert('반납 성공');</script>";


		// 반납 후 해당 책에 예약자가 있는 경우
		$query3="select count(*) from reservation where isbn ='$isbn'";
		$stmt = $conn -> prepare($query3);
		$stmt -> execute();
		$row = $stmt -> fetch(PDO::FETCH_ASSOC);
		if ($row['COUNT(*)'] > 0) {
			$query3="select cno from (select * from reservation where isbn ='$isbn' order by 3) where rownum = 1";
			$stmt = $conn -> prepare($query3);
			$stmt -> execute();
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);
			$next = $row['CNO'];
			$now =  date("Y/m/d");
			$timestamp = strtotime("+11 days");
			$returndate = date("Y/m/d",$timestamp);
			// 오늘 날로부터 11일 뒤를 반납일로 하여 자동 대여
			$query2 = "update ebook set daterented ='$now',datedue = '$returndate', cno = '$next' where isbn = '$isbn'";
			$stmt2 = $conn -> prepare($query2);
			$stmt2 -> execute();

			$query = "DELETE RESERVATION WHERE cno = '$next' and isbn = '$isbn'";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
			echo "<script>alert('예약자에게 자동 대여됨'); location.href='/myrent.php';</script>";
		}




       
      }
	  if(isset($_POST['ext'])){ //연장 post
		$username=$_SESSION['id'];
        $isbn=$_POST['ext'];
		$query = "select datedue,exttimes from ebook where isbn = '$isbn'";
		$q = "select count(*) from reservation where isbn = '$isbn'";
		$s = $conn -> prepare($q);
		$s -> execute();
		$r = $s->fetch(PDO::FETCH_ASSOC);

		// 해당 책에 예약 중인 사람이 있다면
		if ($r['COUNT(*)'] > 0){
			echo "<script>alert('예약 중인 도서이므로 연장이 불가능합니다..');</script>";
		}
		else{ // 없을 경우 exttimes 체크하여 연장 or 연장x
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ( $row['EXTTIMES'] > 1){ // 이미 3번 연장
				echo "<script>alert('더 이상 연장이 불가능합니다.');</script>";
			}else{ // 아직 기회 남음
				$date ="20".$row['DATEDUE'];
				$date = date("Y-m-d",strtotime($date));
				$datedue = date("Y/m/d",strtotime($date));
				$timestamp = strtotime("$date +10 days");
				$extdue = date("Y/m/d",$timestamp);
				$query="update ebook set EXTTIMES=EXTTIMES+1, DATEDUE = '$extdue' where isbn = '$isbn' and cno ='$username'";
				$stmt = $conn -> prepare($query);
				$stmt -> execute();
				$opp = 1 - $row['EXTTIMES'];
				echo "<script>alert('10일 연장되었습니다.');</script>";
				echo "<script>alert('남은 연장 횟수 : '+$opp+' 회');</script>";
			}	
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
            <h1>대여 중</h1>
                <table class ="book table" style="align:center; padding:30px;">
			<thead style="background-color:gray;">
				<tr>
					<th> 번호 </th>
					<th> 제목 </th>
					<th> 출판사 </th>
					<th> 대여 일 </th>
					<th> 반납 예정 </th>
					<th> 연장 </th>
					<th> 반납 </th>
				</tr>
			</thead>
			<tbody>
				<form method="post" action="myrent.php" >

<?php 
                  $cno = $_SESSION['id'];

		$query = "select ISBN,TITLE,PUBLISHER,daterented,datedue,exttimes from ebook where cno = '$cno'";
		$stmt = $conn -> prepare($query);
		$stmt -> execute();
		
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
?>			

			<tr >
				<td style="width:100px;"><?= $row['ISBN']?></td>
				<td style="width:400px;"><?= $row['TITLE']?></td>
				<td style="width:150px;"><?= $row['PUBLISHER'] ?></td>
				<td style="width:100px;"><?= $row['DATERENTED'] ?></td>
				<td style="width:100px;"><?= $row['DATEDUE'] ?></td>
				<td><input  type="submit" value=<?= $row['ISBN']?> name="ext"></td>
				<td><input  type="submit" value=<?= $row['ISBN']?> name="isbn"></td>
			</tr>

<?php
		}
?>
	</form>

			</tbody>
		</table>
        </div>
    </body>
</html>
