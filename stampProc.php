<?php
    include($_SERVER["DOCUMENT_ROOT"]."/inc/common.php");
    include($_SERVER["DOCUMENT_ROOT"]."/inc/DBConnect.php");

    $nickname = $_SESSION['nickname'];
    $stamp = $_POST['stamp'];

    $stamp;
    $resultStamp;
    $resultStampType;
    $regDate;

    switch ($stamp) {
        case 'abc':
            $resultStamp = 'abc';
            $resultStampType = 'lib';
            break;
    }

    $stmt = $conn->prepare("SELECT * FROM stampInfo WHERE nickname = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('s', $nickname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $status = 'normal';
        $regDate = date('Y-m-d H:i:s');

        if ($resultStampType == 'lib') {
            $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, lib01, regDate) VALUES (?, ?, ?, ?)");
        } else if ($resultStampType == 'book') {
            $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, book01, regDate) VALUES (?, ?, ?, ?)");
        }

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('ssss', $nickname, $status, $resultStamp, $regDate);
    } else {
        $row = $result->fetch_assoc();

        if ($resultStampType == 'lib') {
            if (!$row['lib01']) {
                $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, lib01, regDate) VALUES (?, ?, ?, ?)");
            } else if (!$row['lib02']) {
                $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, lib02, regDate) VALUES (?, ?, ?, ?)");
            } else if (!$row['lib03']) {
                $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, lib03, regDate) VALUES (?, ?, ?, ?)");
            } else {
                echo "
                <script>
                    alert('도서관 스탬프는 전부 등록이 완료되었습니다.');
                    location.href = 'stamp03.html';
                </script>";

                mysqli_close($conn);
            }
        } else if ($resultStampType == 'book') {
            if (!$row['book01']) {
                $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, book01, regDate) VALUES (?, ?, ?, ?)");
            } else if (!$row['book02']) {
                $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, book02, regDate) VALUES (?, ?, ?, ?)");
            } else if (!$row['book03']) {
                $stmt = $conn->prepare("INSERT INTO stampInfo (nickname, status, book03, regDate) VALUES (?, ?, ?, ?)");
            } else {
                echo "
                <script>
                    alert('동네서점 스탬프는 전부 등록이 완료되었습니다.');
                    location.href = 'stamp03.html';
                </script>";

                mysqli_close($conn);
            }
        } else {
            echo "
            <script>
                alert('오류가 발생하였습니다.\\n오류가 지속되면 관리자에게 문의바랍니다.');
                history.back();
            </script>";

            mysqli_close($conn);
        }

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('ssss', $nickname, $status, $resultStamp, $regDate);
    }

    if ($stmt->execute()) {
        echo "
        <script>
            alert('스탬프가 등록되었습니다.');
            location.href = 'stamp03.html';
        </script>";
    } else {
        echo "
        <script>
            alert('오류가 발생하였습니다.\\n오류가 지속되면 관리자에게 문의바랍니다.');
            history.back();
        </script>";
    }

    mysqli_close($conn);
?>