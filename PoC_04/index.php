

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DB Explorer</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/fontawesome.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- Leave those next 4 lines if you care about users using IE8 -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
      h1 {
        text-align: center;
      }

    </style>
  </head>
  <body>

  <div>
  <?php
        // Handle form submission
          $host = 'traefik';
          $username = 'admin';
          $password = '';
          $database = 'db';

          
      
          // Create a database connection
          try {
            // Create a PDO connection
            $pdo = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
        
            // Set PDO error mode to exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT pg_is_in_recovery()";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $write=false;

            if ($result && $result['pg_is_in_recovery'] == 1) {
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                You are connected in a read-only slave.
              </div>";
            } else {
              echo "<div class=\"alert alert-success\" role=\"alert\">
              You are connected to a writable database
            </div>";
            $write=true;

            }
            if ($write && $_SERVER["REQUEST_METHOD"] === "POST") {

              $stmt = $pdo->prepare("INSERT INTO log_mock (log_timestamp) VALUES (NOW())");
              $stmt->execute();
            }
        } catch (PDOException $e) {
          die("Insertion failed: " . $e->getMessage());
      }
      
        ?>
    </div>

  <div class="container">
    <div class="jumbotron">
      <h1> View database entries </h1>
      <form method="post">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
  </div>

  </div>



  <div class="container">
    <div class="row">

    <div class=col-4>
        <h2>Data</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
  <?php
    // Database connection parameters
    $host = 'traefik';
    $username = 'admin';
    $password = '';
    $database = 'db';

    // Create a database connection
    try {
      // Create a PDO connection
      $pdo = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
  
      // Set PDO error mode to exceptions
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      // Query the log_mock table
      $query = "SELECT * FROM log_mock ORDER BY id DESC";
      $stmt = $pdo->query($query);
  
      // Display the data in the table

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $row['id'] . '</td>';
          echo '<td>' . $row['log_timestamp'] . '</td>';
          echo '</tr>';
      }

  
      // Close the database connection (optional, PDO does this automatically when the script ends)
      // $pdo = null;
  } catch (PDOException $e) {
      print("Connection failed: " . $e->getMessage());
  }
    ?>
      </tbody>
        </table>
</div>

    <div class=col-4>
        <h2>Master Data</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
  <?php
    // Database connection parameters
    $host = 'postgresql-master';
    $username = 'admin';
    $password = '';
    $database = 'db';

    // Create a database connection
    try {
      // Create a PDO connection
      $pdo = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
  
      // Set PDO error mode to exceptions
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      // Query the log_mock table
      $query = "SELECT * FROM log_mock ORDER BY id DESC";
      $stmt = $pdo->query($query);
  
      // Display the data in the table

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $row['id'] . '</td>';
          echo '<td>' . $row['log_timestamp'] . '</td>';
          echo '</tr>';
      }

  
      // Close the database connection (optional, PDO does this automatically when the script ends)
      // $pdo = null;
  } catch (PDOException $e) {
    print("Connection failed: " . $e->getMessage());
  }
    ?>
      </tbody>
        </table>
</div>

<div class=col-4>
        <h2>Slave Data</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
  <?php
    // Database connection parameters
    $host = 'postgresql-slave';
    $username = 'admin';
    $password = '';
    $database = 'db';

    // Create a database connection
    try {
      // Create a PDO connection
      $pdo = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
  
      // Set PDO error mode to exceptions
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      // Query the log_mock table
      $query = "SELECT * FROM log_mock ORDER BY id DESC";
      $stmt = $pdo->query($query);
  
      // Display the data in the table

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $row['id'] . '</td>';
          echo '<td>' . $row['log_timestamp'] . '</td>';
          echo '</tr>';
      }

  
      // Close the database connection (optional, PDO does this automatically when the script ends)
      // $pdo = null;
  } catch (PDOException $e) {
      die("Connection failed: " . $e->getMessage());
  }
    ?>
      </tbody>
        </table>
</div>

</div>

</div>



    <!-- Including Bootstrap JS (with its jQuery dependency) so that dynamic components work -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>