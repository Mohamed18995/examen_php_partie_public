<?php
  include_once("lib/home.php");
  $page_view ="detail";
  $item_id = isset($_GET["id"]) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : null;
  $item = getOneArticle($item_id);
?>