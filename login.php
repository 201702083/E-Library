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
  $_SESSION['login']='NO';
  $_SESSION['id']=null;
?>



<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1">
	<title>LOGIN</title>
    </head>
    <body>
         
        <h1 class="title">
            CNU Ebook
        </h1>
        <!-- button 클릭 시 id 와 pw 를 logincheck로 post 전달 -->
        <form method="post" action="logincheck.php" >
        <div class="loginbox">
            <div class="idpw">
                <p>
                    ID  <input type="text" name="userid" class="inph">
                </p>
                <p>
                    PW<input type="password" name="userpw" class="inph">
                </p>
            </div>
            <div class="check">
                <p>
                     <button type="submit" id="btn" >
                        로그인
                    </button>
                    </form >
                </p>
                <p>
                    <a href="./not.php">
                        분실
                    </a>
                    <a href="./not.php">
                        가입
                    </a>
                </p>
            </div>
        </div>
        </form>
    </body>
</html>