<?php
    if(session_status()!=PHP_SESSION_ACTIVE) session_start();
    $_SESSION['login']='';
    $_SESSION['id']='';
    $_SESSION['status']='';
    session_destroy();
    print "<script language='Javascript' type='text/javascript'>
    window.location.href = '../../index.php';
    </script>";
?>