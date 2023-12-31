<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>
  </head>
  <body>
    <div style="margin: 2em">
    <h1>Welcome to WS2!</h1>
    <?php 
        
    echo "<p>Host: " . getenv('HOSTNAME') . "</p>"; 

    ?>

    </div>
  </body>
</html>