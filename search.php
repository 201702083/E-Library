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

<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1">
	<title>SEARCH</title>
	
</head>
<body>
	<div>
		<h2 style="text-align:center;">EBOOK LIST</h2>
		            <button type="button" style= "position:fixed; top : 10px; left : 10px;" onclick="location.href='main.php'" >Back</button>

		<table class ="book table" style="margin-right:auto;margin-left:auto;">
			<thead style="background-color:gray;">
				<tr>
					<th style="padding:10px;"> 번호 </th>
					<th style="padding:10px;"> 제목 </th>
<Input text="">
					<th style="padding:10px;"> 출판사 </th>
					<th style="padding:10px;"> 대여 </th>
				</tr>
			</thead>
			<tbody>
<?php 
		$find = $_POST['search']; // post 받은 search 단어를 받고
		$query = "select * from ebook where title LIKE '%$find%' or publisher LIKE '%$find%'
					union
					select e.* from ebook e, authors a where a.author LIKE '%$find%' and a.isbn = e.isbn";
		// 단어를 포함하는 or 검색을 한다.
		$stmt = $conn -> prepare($query);
		$stmt -> execute();

		// 검색 결과 출력
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
?>			
	<form method="post" action="bookrent.php" >

			<tr>
				<td style="width:100px;padding:10px;text-align:center;"><?= $row['ISBN']?></td>
				<td style="width:500px;padding:10px;"><?= $row['TITLE']?></td>
				<td style="width:200px;padding:10px;"><?= $row['PUBLISHER'] ?></td>
				<td><input  type="submit" value=<?= $row['ISBN']?> name="isbn"></td>
			</tr>
			</form>

<?php
		}
?>
			</tbody>
		</table>
		
	</div>
</body>
</html>
