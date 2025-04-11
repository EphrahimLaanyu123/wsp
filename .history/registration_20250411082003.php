<?php
$host = "localhost";      // or your DB host
$dbname = "wsp"; // change to your database name
$username = "root";
$password = "";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Collect and sanitize form data
    $patientId = $_POST['patientId'];
    $firstName = $_POST['firstName'];
    $surname = $_POST['surname'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $gender = $_POST['gender'];
    $county = $_POST['county'];

    $nokFirstName = $_POST['nokFirstName'];
    $nokSurname = $_POST['nokSurname'];
    $nokRelationship = $_POST['nokRelationship'];

    // Insert patient data
    $stmt = $conn->prepare("INSERT INTO patients (patient_id, first_name, surname, date_of_birth, gender, county)
                            VALUES (:patient_id, :first_name, :surname, :date_of_birth, :gender, :county)");

    $stmt->execute([
        ':patient_id' => $patientId,
        ':first_name' => $firstName,
        ':surname' => $surname,
        ':date_of_birth' => $dateOfBirth,
        ':gender' => $gender,
        ':county' => $county
    ]);

    // Insert next of kin data
    $stmt2 = $conn->prepare("INSERT INTO next_of_kin (patient_id, first_name, surname, relationship)
                             VALUES (:patient_id, :first_name, :surname, :relationship)");

    $stmt2->execute([
        ':patient_id' => $patientId,
        ':first_name' => $nokFirstName,
        ':surname' => $nokSurname,
        ':relationship' => $nokRelationship
    ]);

    echo "Patient registration successful.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
