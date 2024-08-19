<?php
    include($_SERVER["DOCUMENT_ROOT"]."/inc/DBConnect.php");

    $stampLib01 = '';
    $stampLib02 = '';
    $stampLib03 = '';
    $stampBook01 = '';
    $stampBook02 = '';

    if (!isset($_SESSION['nickname'])) {
        /*echo "
            <script>
                alert('로그인을 먼저 해주세요.');
                location.href='login.html';
            </script>";*/
        return;
    }

    $nickname = $_SESSION['nickname'];

    $stmt = $conn->prepare("SELECT * FROM userInfo WHERE nickname = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('s', $nickname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        /*echo "
            <script>
                alert('로그인을 먼저 해주세요.\\n등록이 되었는데 오류가 발생한 경우 관리자에게 문의바랍니다.');
                location.href='login.html';
            </script>
        ";*/
        return;
    }

    $row = $result->fetch_assoc();

    if ($row['lib01']) {
        $stampLib01 = $row['lib01'];
    }
    if ($row['lib02']) {
        $stampLib02 = $row['lib02'];
    }
    if ($row['lib03']) {
        $stampLib03 = $row['lib03'];
    }
    if ($row['book01']) {
        $stampBook01 = $row['book01'];
    }
    if ($row['book02']) {
        $stampBook02 = $row['book02'];
    }

    mysqli_close($conn);
?>