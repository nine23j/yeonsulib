<?php
    include($_SERVER["DOCUMENT_ROOT"]."/inc/common.php");

    session_destroy();
?>
<script>
    location.replace('index.html');
</script>