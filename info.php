<?php

$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$url_components = parse_url($url);

parse_str($url_components['query'], $params);
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Info</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class=" navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./index.php">Olympic winners</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./best_test.php">Best olympic athletes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login/login.php">Login</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container text-center">
    <h1 class="header">Olympic games</h1>
    <div id="filter-div">

    </div>
    <div id="info"></div>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
<script src="infoTable.js">
    const id = <?php echo $params['id'] ?>
</script>
</body>
</html>
