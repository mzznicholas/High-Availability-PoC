<?php
session_start();

if (isset($_POST['doctor_id'])) {
    $_SESSION['doctor_id'] = $_POST['doctor_id'];
    header('Location: index.php');
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Doctor Login</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="login.php">
                        <div class="form-group">
                            <label for="doctor_id">Doctor ID</label>
                            <input type="text" class="form-control" id="doctor_id" name="doctor_id" placeholder="12345" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap JS (optional, for some features) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

