<?php
    $servername = "localhost";  // 데이터베이스 서버 주소
    $username = "yeonsulib";     // 데이터베이스 계정 사용자명
    $password = "yspublic1324!";     // 데이터베이스 계정 비밀번호
    $dbname = "yeonsulib";       // 사용할 데이터베이스 이름

    $conn = new mysqli($servername, $username, $password, $dbname);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
