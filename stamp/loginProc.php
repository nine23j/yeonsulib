<?php
    include($_SERVER["DOCUMENT_ROOT"]."/inc/common.php");
    include($_SERVER["DOCUMENT_ROOT"]."/inc/DBConnect.php");

    $nickname = $_POST['nickname'];
    $password = hash('sha256', $_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM userInfo WHERE nickname = ? AND password = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('ss', $nickname, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "
        <script>
            alert('일치하는 닉네임이 없거나 비밀번호가 일치하지 않습니다.');
            history.back();
        </script>";

        exit();
    }

    $row = $result->fetch_assoc();

    $_SESSION['nickname'] = $row['nickname'];
    mysqli_close($conn);
    header("Location: https://yeonsulib.com/stamp/index.html");
?>