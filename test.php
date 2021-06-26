<!DOCTYPE html>
<!--해야할 것-->
<html lang="ko">
	<head>
		<meta charset="utf-8">
		<meta name = "viewport" content="width=device-width, initial-scale=1">
		<title>Function</title>
	
	</head>
	<body>
		
		<h1> 구현 기능 </h1>
		
		<li>로그인 : select query</li><br>
		<li>책 리스트 조회 : select query</li><br>
		<li>도서명, 출판사, 저자 OR 검색 : union</li><br>
		<li>대여 기능 - 인당 최대 3권, 최대 2회 연장 : 대여가 가능한 책은 date컬럼들이 null 인 상태 , 대여 신청된 책들은 date컬럼들이 set된 상태</li><br>
		<li>예약 기능 - 예약된 책 연장 불가, 예약자 자동 대여 : reservation 테이블에서 조회하여 연장 가능 판단, 반납 시 예약자를 조회하여 자동 대여 ( 이메일 구현 못함 ) </li><br>
		<li>자동 반납 기능 - nowtime 과 비교하여 로그인 시 자동 반납 </li><br>
		<li>패스워드, 이메일 변경 기능 : update query</li><br>
		<li>대여 중, 예약 중, 대여 기록 조회 : select query</li><br>
		<li>관리자 접속 - 통계, 책 추가 : 관리자용 계정은 id/pw   1/admin 으로 접속 가능 . select , insert</li><br>

		
		<h1> 구현 예정 </h1>
		
		<li>공지사항</li><br>
		<li>도서 업데이트 일지</li><br>
		<li>이용 안내</li><br>

	</body>

</html>
