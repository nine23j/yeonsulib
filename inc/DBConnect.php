<?php
    $servername = "";  // 데이터베이스 서버 주소
    $username = "";     // 데이터베이스 계정 사용자명
    $password = "";     // 데이터베이스 계정 비밀번호
    $dbname = "";       // 사용할 데이터베이스 이름

    $conn = new mysqli($servername, $username, $password, $dbname);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
