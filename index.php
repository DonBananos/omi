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

<!DOCTYPE html>
<html>
    <head>
        <title>Online Movie Index</title>
        <?php require './includes/header.html'; ?>
    </head>
    <body>
        <?php require './includes/loginBar.php'; ?>
        <div class="main-container">

            <div class="container">
                <!-- Example row of columns -->
                <div class="row">
                    <div class="col-md-8">
                        <h2>Welcome</h2>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
                        <small>// CEO CincoWare</small>
                    </div>
                    <div class="col-md-4">
                        <?php require './user/register.php'; ?>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- main-container -->
        <hr>

        <?php require './includes/footer.php'; ?>

    </body>
</html>
