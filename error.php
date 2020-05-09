<?php
    


?>


<html>
    <head>
        <title>Bowling Database</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bowling.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700&display=swap" rel="stylesheet">
    </head>
    <body onload="getErrorMessage()">
        <div class="container-fluid">
            <div class="container-fluid title" >
                <h1 class="title__text">Bowling Database</h1>
            </div>
            <div class="container-fluid text-center">
              <h2 class="error_text">An error has occurred with the database entry.</h2>
              <h2 class="error_text">Please check your query and try again.</h2>

              <form>
                <input type="button" value="Return To Previous Page" onclick="history.back(2)" id="returnBtn" class="btn btn-md btn-danger">
              </form>
            </div>
            <div class="container-fluid text-center error">
                <h4>SQL Error generated</h4>
                <p class="error_message"></p>
            </div>
        </div>
   </body>
</html>