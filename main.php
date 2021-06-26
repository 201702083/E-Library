<?php
  header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1">
	<title>HOME</title>
    <link rel="stylesheet" href="../style.css">
    </head>
    <body>
            <button type="button" style= "position:fixed; top : 10px; left : 10px;" onclick="location.href='enter.php'" >Back</button>

        <h1 class="title" style="margin: 0; padding: 0;">
            Ebook Library
        </h1>
        <div class="back">
            <div class="category">
                <div class="info">
                    <h2 style="font-size: 35px;">
                        Information
                    </h2>
                    <li><a href="./not.php" style="text-decoration-line : none;">공지사항</a></li><br>
                    <li><a href="./not.php" style="text-decoration-line : none;">이용안내</a></li><br>
                    <li><a href="./not.php" style="text-decoration-line : none;">도서업데이트</a></li><br>
                </div>
                <div class="books">
                    <h2 style="font-size: 35px;">Ebook</h2>
                    <li><a href ="./books.php" style="text-decoration-line : none;">List</a></li>
                    <br>
	                <form method="post" action="search.php" >
                        <li><input type="text" name="search"><input type="submit" value ="검색">
                        <br>(도서명, 출판사, 저자 검색)</li>
                    </form>
                </div>
            </div>
        </div>
</html>
