<?php 
include_once("lib/home.php");
$page_view = "home";

$quantity = getTotalArticles();
$article_every_page = 30;
// ceil : 1.5 on prend 2 : prendre le nombre entier le plus proche
$last_page =  ceil($quantity / $article_every_page);
$next_confirmed = 10;
$previous_confirmed  = 3;
$get_current_page = isset($_GET["start"]) ? filter_input(INPUT_GET, 'start', FILTER_SANITIZE_NUMBER_INT) : 1;
$details_numeration = pagination($next_confirmed , $previous_confirmed , $get_current_page ,$last_page );

$next = $details_numeration["next_nb"];
$prev = $details_numeration["prev_nb"];
$calcul_place = ($get_current_page - 1) * $article_every_page;
$result = getArticles($article_every_page , $calcul_place);
$articles = array_chunk($result, 3);

?>