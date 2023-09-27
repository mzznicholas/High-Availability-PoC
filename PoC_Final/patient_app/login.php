<?php
session_start();

if (isset($_POST['patient_fc'])) {
    $_SESSION['patient_fc'] = $_POST['patient_fc'];
    header('Location: index.php');
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Patient Login</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="login.php">
                        <div class="form-group">
                            <label for="patient_fc">Fiscal Code</label>
                            <input type="text" class="form-control" id="patient_fc" name="patient_fc" placeholder="ABCDEF12G34H567G" required>
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

