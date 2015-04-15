<!--
Author: R. Mike Jensen, Heini L. Ovason
-->


<?php
require './includes/config/config.php';

if (isset($_POST['search'])) {
    //Replaces all spaces with + (for search)
    $title = preg_replace("/ /", '+', $_POST['search_field']);

    //HTTP request to OMDb API with JSON answer
    $json = file_get_contents("http://www.omdbapi.com/?t=$title&y=&plot=short&r=json&type=movie");

    //JSON decode of answer
    $data = json_decode($json, true);
}
?>

<?php require './includes/header.php'; ?>
<?php require './includes/loginBar.php'; ?>
<?php require './includes/start.php'; ?>
<?php require './includes/footer.php'; ?>



