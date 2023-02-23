<?php
require_once "../connect/db.php";
require_once "func.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$g = $_GET['g'];
$nc = $_GET['nc'];
$sort = $_GET['sort'];

$query = "SELECT DISTINCT title, img, id_year FROM anime JOIN ganres_for_title ON anime.id = ganres_for_title.id_anime JOIN genres_names ON ganres_for_title.genre_key = genres_names.genre_key JOIN age_rating ON anime.id_raiting=age_rating.id ";
if($g || $nc || $sort){
    if($g || $nc){
    $query.=" WHERE ";
    }
    if($g){
        $query.=" genres_names.g_name ='$g' ";
    }
    if($g && $nc ) { $query.=" AND ";}
    if($nc){
        $query.= "age_rating.rating ='$nc' ";
    }
    if($sort){
        switch ($sort) {
            case '1':
               $query.=" ORDER BY title ASC";
            break;
            case '2':
                $query.=" ORDER BY title DESC";
                break;
            case '3':
                $query.=" ORDER BY anime.id_year DESC";
                break;
            
            default:
                break;
        }
    }
    // var_dump($query);
    echo getContentAnime($link, $query);
}

?>