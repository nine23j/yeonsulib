<?php
    include($_SERVER["DOCUMENT_ROOT"]."/inc/common.php");
    include($_SERVER["DOCUMENT_ROOT"]."/inc/DBConnect.php");

    if (!isset($_SESSION['nickname'])) {
        echo "
            <script>
                alert('로그인을 먼저 해주세요.');
                location.href='login.html';
            </script>";
    }

    $nickname = $_SESSION['nickname'];

    $stmt = $conn->prepare("SELECT lib01, lib02, lib03, book01, book02 FROM userInfo WHERE nickname = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('s', $nickname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "
        <script>
            alert('오류가 발생하였습니다.\\n오류가 지속되면 관리자에게 문의바랍니다.');
            location.href='stamp02.html';
        </script>";

        exit();
    }

    $row = $result->fetch_assoc();

    foreach($row as $key => $value) {
        if ($key == 'status') {
            continue;
        }

        if (!$value) {
            echo "
            <script>
                alert('스탬프를 전부 등록하지 않으셨습니다.');
                location.href='stamp02.html';
            </script>";
            exit();
        }
    }

    if ($row['status'] == 'gift') {
        echo "
            <script>
                alert('이미 선물을 받으셨습니다.');
                location.href='stamp02.html';
            </script>";

        exit();
    } else if ($row['status'] == 'normal') {
        $status = 'gift';
        $giftDate = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("UPDATE userInfo SET status = ?, giftDate = ? WHERE nickname = ?");

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('sss', $status, $giftDate, $nickname);

        if ($stmt->execute()) {
            echo "
                <script>
                    alert('선물 수령확인되었습니다.');
                    location.href='stamp02.html';
                </script>";

            exit();
        } else {
            echo "
                <script>
                    alert('오류가 발생하였습니다.\\n지속될 경우 관리자에게 문의바랍니다.');
                    location.href='stamp02.html';
                </script>";

            exit();
        }
    }
?>