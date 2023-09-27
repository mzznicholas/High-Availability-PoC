<?php
session_start();

if (!isset($_SESSION['doctor_id'])) {
    header('Location: login.php');
    die();
}

$doctorId = $_SESSION['doctor_id'];
$doctorData = null;
$messages = null;
$patients = null;

try {
    $host = 'traefik_traefik';
    $username = 'admin';
    $password = '';
    $database = 'db';
    // Create a new PDO connection
    $pdo = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if a patient ID is provided
    if ($doctorId !== null) {
        // Prepare and execute a query to retrieve patient data
        $stmt = $pdo->prepare("SELECT * FROM doctor WHERE id = :doctor_id");
        $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_STR);
        $stmt->execute();
        // Fetch the patient data
        $doctorData = $stmt->fetch(PDO::FETCH_ASSOC);


        $stmt = $pdo->prepare("SELECT * FROM patient");
        $stmt->execute();
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT d.id AS doctor_id, d.name AS doctor_name, p.name AS patient_name, content, specialization, created_at FROM (patient p INNER JOIN message m ON p.id = m.patient_id) 
                                     INNER JOIN doctor d ON d.id = m.doctor_id
                                     WHERE doctor_id = :doctor_id ORDER BY created_at DESC");
        $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_STR);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Doctor App</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/fontawesome.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- Leave those next 4 lines if you care about users using IE8 -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    </style>
</head>
<body>

<div class="jumbotron">
    <h1>Welcome <?= $doctorData['name'] ?></h1>
    <p><a class="btn btn-outline-danger" href="logout.php" role="button">Close</a></p>

</div>

<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Patients</h3>
                </div>
                <div class="card-body">
                    <?php
                    foreach ($patients as $patientData):
                        ?>
                        <p class="card-text">Name: <?= $patientData['name'] ?></p>
                        <p class="card-text">Fiscal code: <?= $patientData['fc'] ?></p>
                        <p class="card-text">Emergency contact: <?= $patientData['emergency_phone'] ?></p>
                        <p class="card-text">Blood type: <?= $patientData['blood_type'] ?></p>
                        <p>Allergies:</p>
                        <ul>
                            <?php
                            $values = array_map('trim', explode(',', $patientData['allergies']));
                            foreach ($values as $value):
                                ?>
                                <li><?php echo $value; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <hr>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Received messages</h3>
                        </div>
                        <div class="card-body">

                            <?php
                            foreach ($messages as $row) {
                                echo "<p>From: <b>" . $row["patient_name"] . "</b> To: <b>" . $row['doctor_name'] . "</b> (" . $row["specialization"] . ")</p>";
                                ?>
                                <div class="alert alert-primary" role="alert">
                                    <?= $row["content"] ?>
                                </div>
                                <hr>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Including Bootstrap JS (with its jQuery dependency) so that dynamic components work -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>