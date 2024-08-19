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

    $lockPosition = $_POST['lockPosition'];
    $code = $_POST['code'];
    $type;

    switch ($lockPosition) {
        case 'lib01':
        case 'lib02':
        case 'lib03':
        case 'book01':
        case 'book02':
            break;
        default:
            echo "
            <script>
                alert('잘못된 접근입니다.');
                location.href='stamp02.html';
            </script>";

            exit();
    }

    switch ($code) {
        case 'stamp01':
        case 'stamp02':
            $type = 'book';
        case 'stamp03':
        case 'stamp04':
        case 'stamp05':
        case 'stamp06':
        case 'stamp07':
        case 'stamp08':
        case 'stamp09':
        case 'stamp10':
        case 'stamp11':
        case 'stamp12':
        case 'stamp13':
        case 'stamp14':
        case 'stamp15':
        case 'stamp16':
        case 'stamp17':
            $type = 'lib';
            break;
        default:
            echo "
                <script>
                    alert('올바른 스탬프 QR이 아닙니다.');
                    location.href='stamp02.html';
                </script>";

            exit();
    }

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

    // 이미 등록된 스탬프인지 검증
    foreach($row as $key => $value) {
        if ($value == $code) {
            echo "
            <script>
                alert('이미 등록된 스탬프입니다.');
                location.href='stamp02.html';
            </script>";
            exit();
        }
    }

    // 자물쇠 자리 비어있는지 검증
    if ($row[$lockPosition]) {
        echo "
        <script>
            alert('다른 자물쇠 자리를 클릭해주세요.');
            location.href='stamp02.html';
        </script>";
    }


    $sql = "UPDATE userInfo SET ".$lockPosition." = ? WHERE nickname = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('ss', $code, $nickname);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('스탬프 등록이 완료되었습니다.');
            location.href='stamp02.html';
        </script>";
    } else {
        echo "
        <script>
            alert('오류가 발생하였습니다.\\n오류가 지속되면 관리자에게 문의바랍니다.');
            location.href='stamp02.html';
        </script>";
    }
?>