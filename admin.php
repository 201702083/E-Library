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
	<title>ADMIN PAGE</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <body style="background-color: antiquewhite;">
        <h1 class="title" style="margin: 0; padding: 0;">
            Ebook Library
        </h1>
            <button type="button" style= "position:fixed; top : 10px; left : 10px;" onclick="location.href='login.php'" >LogOut</button>

        <div class="userinfo">
            <h1>Admin page</h1>

        </div>
        <div style="height: 130px;">
        </div>


        <div class="back" style="width: auto; margin: 0px 50px;">

            <h1>Best book</h1>
            <div style="background-color:white;">
            <li><?php  // 가장 많이 대여된 책을 출력한다.
                    $query = "select isbn,cnt,title,publisher from ( select isbn , count(*) as cnt from previousrental group by isbn) natural join ebook order by 1 ";
                    $stmt = $conn -> prepare($query);
		            $stmt -> execute();
                    $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                    $isbn = $row['ISBN'];
                    $title = $row['TITLE'];
                    $pub = $row['PUBLISHER'];
                    $cnt = $row['CNT'];
                    echo "$isbn | $title | $pub ------- 총 $cnt 회 대여";
            ?></li>


            <h1>Who borrowed the most</h1>
            <div style="background-color:white;">
            <li><?php  // 가장 많은 대여 횟수를 가진 회원을 출력한다.
                    $query = "select * from (select * from ( select cno cno, count(*) cnt from previousrental group by cno ) natural join customer order by cnt desc) where rownum=1;";
                    $stmt = $conn -> prepare($query);
		            $stmt -> execute();
                    $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                    $cno = $row['CNO'];
                    $name = $row['NAME'];
                    $cnt = $row['CNT'];
                    echo "$cno | $name ------- 총 $cnt 회 대여";
            ?></li>


            <h1>Who borrowed the book the longest</h1>
            <div style="background-color:white;">
            <li><?php  // 가장 많은 대여 기간을 가진 회원을 출력한다.
                    $query = "select cno , name , read, row_number() over (order by read desc) rank from ( select cno cno, sum(datereturned - daterented) read from previousrental group by cno) natural join customer";
                    $stmt = $conn -> prepare($query);
		            $stmt -> execute();
                    $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                    $cno = $row['CNO'];
                    $name = $row['NAME'];
                    $read = $row['READ'];
                    echo "$cno | $name ------- 총 $read 일 대여";
            ?></li>

            <!--책 추가 기능-->
            <h1>Add Ebook</h1>
            <div style="background-color:white;">
            <form methoe="post" action = "addbook.php">
                <input type="text" name="title" placeholder="TITLE"/>
                <input type="text" name="pub" placeholder="PUBLISHER"/>
                <input type="text" name="author" placeholder="AUTHOR"/>
                <input type="text" name="year" placeholder="YEAR yyyy/mm/dd"/>
                <input type="submit" value="추가"/>
            </form>
        </div>
    </body>
</html>
