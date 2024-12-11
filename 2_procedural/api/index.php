<?php
/**
 * Main backend API
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0 January 2019
 */
require_once('movie.php');

switch($_POST['action']) {
    case 'load':
        echo json_encode(listAll());
        break;
    case 'search':
        echo json_encode(search($_POST['movie_search_text']));
        break;
    case 'add':
        echo json_encode(add($_POST['movie_name']));
        break;
    case 'modify':
        echo json_encode(update($_POST['movie_id'], $_POST['movie_name']));
        break;
    case 'delete':
        echo json_encode(delete($_POST['movie_id']));
        break;
}