<?php
require_once "../connect/db.php";
// require_once "func.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$userId = $_SESSION['id'];

if ($_FILES){
    $filename = $userId . substr(basename($_FILES['userphoto']['name']), -4);
    $uploaddir = '../imgs/users/';
    $uploadfile = $uploaddir . $filename;

    if (move_uploaded_file($_FILES['userphoto']['tmp_name'], $uploadfile)) {
        $query = "UPDATE users SET img='".$filename."' WHERE id = " . $userId;
        mysqli_query($link, $query) or die("1Ошибка " . mysqli_error($link));
        $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
        if($rez){
            echo "<script>window.location.href='../user_room.php';</script>"; 
        }
    } else {
        echo "<script>alert('Ошибка');</script>";
    }

}
?>