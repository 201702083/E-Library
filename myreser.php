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
	<title>MY RESERVATION</title>
    <link rel="stylesheet" href="./style.css">
    <?php
      if(isset($_POST['isbn']) ){//post방식으로 데이터가 보내졌는지?
        $username=$_SESSION['id'];
        $isbn=$_POST['isbn'];
		// 예약내역에서 취소한다.
		$q= "DELETE FROM RESERVATION WHERE ISBN = '$isbn'";
		$stmt = $conn -> prepare($q);
		$stmt -> execute();
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
            <h1>예약 내역</h1>
                <table class ="book table" style="align:center; padding:30px;">
			<thead style="background-color:gray;">
				<tr>
					<th> 번호 </th>
					<th> 제목 </th>
					<th> 출판사 </th>
					<th> 예약일시 </th>
					<th> 취소 </th>
				</tr>
			</thead>
			<tbody>

				<!--버튼을 누르면 isbn을 보내고 세션id와 함께 검색하여 내역을 지운다.-->
				<form method="post" action="myreser.php" >
<?php 
					$cno = $_SESSION['id'];

					$query = "select e.ISBN,e.TITLE,e.PUBLISHER,r.reservationtime from ebook e,reservation r where r.cno = '$cno' and r.isbn = e.isbn";
					$stmt = $conn -> prepare($query);
					$stmt -> execute();
		
					while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
?>			

						<tr>
							<td style="width:100px;"><?= $row['ISBN']?></td>
							<td style="width:400px;"><?= $row['TITLE']?></td>
							<td style="width:150px;"><?= $row['PUBLISHER'] ?></td>
							<td style="width:100px;"><?= $row['RESERVATIONTIME'] ?></td>
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
