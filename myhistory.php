
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
	<title>MY HISTORY</title>
    <link rel="stylesheet" href="./style.css">
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
            <h1>대여 기록</h1>
                <table class ="book table" style="align:center; padding:30px;">
			<thead style="background-color:gray;">
				<tr>
					<th> 번호 </th>
					<th> 제목 </th>
					<th> 출판사 </th>
					<th> 대여 일 </th>
					<th> 반납 일 </th>
				</tr>
			</thead>
			<tbody>
				<form method="post" action="myrent.php" >

<?php 
        $cno = $_SESSION['id'];


		$query = "select e.ISBN,e.TITLE,e.PUBLISHER,p.daterented,p.datereturned from ebook e,previousrental p where p.cno = '$cno' and p.isbn = e.isbn";
		$stmt = $conn -> prepare($query);
		$stmt -> execute();
		// PREVIOUSRENTAL 테이블에서 세션 id의 대여 기록을 모두 출력한다.
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
?>			

			<tr>
				<td style="width:100px;"><?= $row['ISBN']?></td>
				<td style="width:400px;"><?= $row['TITLE']?></td>
				<td style="width:150px;"><?= $row['PUBLISHER'] ?></td>
				<td style="width:100px;"><?= $row['DATERENTED'] ?></td>
				<td style="width:100px;"><?= $row['DATERETURNED'] ?></td>

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
