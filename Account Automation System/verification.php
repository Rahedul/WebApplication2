<?php
    $conn = mysqli_connect('localhost', 'root', '', 'db_lr');
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_query($conn, "update tabel_user set verification_status='1' where id='$id'");
?>
<script>
    window.location.href = 'login.php';
</script>