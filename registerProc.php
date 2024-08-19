<?php
    include($_SERVER["DOCUMENT_ROOT"]."/inc/common.php");
    include($_SERVER["DOCUMENT_ROOT"]."/inc/DBConnect.php");

    $nickname = $_POST['nickname'];

    $stmt = $conn->prepare("SELECT nickname FROM userInfo WHERE nickname = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('s', $nickname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "
        <script>
            alert('이미 존재하는 닉네임입니다.\\n다른 닉네임을 사용해주세요.');
            history.back();
        </script>";
    } else {
        $nickname = $_POST['nickname'];
        $password = hash('sha256', $_POST['password']);
        $status = 'normal';
        $regDate = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO userInfo (nickname, password, status, regDate) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('ssss', $nickname, $password, $status, $regDate);

        if ($stmt->execute()) {
            echo "
            <script>
                alert('닉네임 등록이 완료되었습니다.\\n로그인 화면으로 이동합니다.');
                location.href = 'login.html';
            </script>";
        } else {
            echo "
            <script>
                alert('오류가 발생하였습니다.\\n오류가 지속되면 관리자에게 문의바랍니다.');
                history.back();
            </script>";
        }
    }

    $conn->close();
?>