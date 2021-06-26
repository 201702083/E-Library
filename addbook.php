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
    // oracle 접속
?>
<?php
  header("Content-Type: text/html; charset=utf-8");
?>
    <?php
        print_r($_POST);
        print_R($_GET);
      if(isset($_GET['title'])&&isset($_GET['pub'])&& isset($_GET['author'])){//post방식으로 데이터가 보내졌는지?
        $title=$_GET['title'];// title 
        $pub=$_GET['pub']; // publisher     
        $author=$_GET['author']; // author
        $year=$_GET['year']; // year

        $query="select isbn from (select isbn from ebook order by isbn desc ) where rownum =1";
        $stmt = $conn -> prepare($query);
		$stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        $newisbn = $row['ISBN']+1;
        $query="insert into ebook values ('$newisbn', '$title', '$pub', '$year', null, 0, null, null)"; // 도서 추가 쿼리
        $query3 ="insert into authors values ('$newisbn', '$author')"; // 저자 추가 쿼리
        $stmt = $conn -> prepare($query); 
		$stmt -> execute();
        $stmt = $conn -> prepare($query3);
        $stmt -> execute();
        echo "<script>alert('도서 추가'); location.href='admin.php';</script>"; // 다시 admin 페이지로
      }
    ?>
