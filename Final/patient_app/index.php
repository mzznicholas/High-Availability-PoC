<?php
session_start();

if (!isset($_SESSION['patient_fc'])) {
    header('Location: login.php');
    die();
}

$patient_fc = $_SESSION['patient_fc'];

$patientData = null;
$messages = null;
try {
    $host = 'traefik';
    $username = 'admin';
    $password = '';
    $database = 'db';
    // Create a new PDO connection
    $pdo = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if a patient ID is provided
    if ($patient_fc !== null) {
        // Prepare and execute a query to retrieve patient data
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE fc = :patient_fc");
        $stmt->bindParam(':patient_fc', $patient_fc, PDO::PARAM_STR);
        $stmt->execute();
        // Fetch the patient data
        $patientData = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION["patient_id"] = $patientData["id"];



        $stmt = $pdo->prepare("SELECT id, name, specialization FROM doctor");
        $stmt->execute();
        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT pg_is_in_recovery()");
        $stmt->execute();
        $isDbOn = $stmt->fetch(PDO::FETCH_ASSOC)['pg_is_in_recovery'] != 1;

        if (isset($_POST["content"])) {

            $stmt = $pdo->prepare("INSERT INTO message (doctor_id, content, patient_id) VALUES (:doctorId, :content, :patientId)");
            $doctorId = $_POST["doctor_id"];
            $content = $_POST["content"];
            $patientId = $_SESSION["patient_id"];

            $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
            $stmt->bindParam(':patientId', $patientId, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->execute();

        }

        $stmt = $pdo->prepare("SELECT d.name AS doctor_name, p.name AS patient_name, content, specialization, created_at FROM (patient p INNER JOIN message m ON p.id = m.patient_id) 
                                     INNER JOIN doctor d ON d.id = m.doctor_id
                                     WHERE fc = :patient_fc ORDER BY created_at DESC");
        $stmt->bindParam(':patient_fc', $patient_fc, PDO::PARAM_STR);
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

    <title>Patient App</title>

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
    <h1>Welcome <?= $patientData['name'] ?></h1>
</div>

<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Your medical card</h3>
                </div>
                <div class="card-body">

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
                    <p><a class="btn btn-outline-danger" href="logout.php" role="button">Close</a></p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <?php
                    if ($isDbOn) {
                        ?>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-center">Send Message to Doctor</h3>
                            </div>
                            <div class="card-body">
                                <form action="index.php" method="post">
                                    <div class="form-group">
                                        <label for="doctor">Select Doctor</label>
                                        <select class="form-control" id="doctor" name="doctor_id" required>
                                            <option value="">Select a doctor</option>
                                            <?php foreach ($doctors as $doctor): ?>
                                                <option value="<?php echo $doctor['id']; ?>"><?php echo $doctor['name'] . "(" . $doctor['specialization'] . ")"; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="content">Content</label>
                                        <textarea class="form-control" id="content" name="content" rows="2"
                                                  required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            Cannot send a message to doctor at the moment.
                        </div>
                    <?php } ?>
                </div>
                <div class="col-12">
                    <div class="card" style="margin-top: 1em">
                        <div class="card-header">
                            <h3 class="text-center">Sent messages</h3>
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