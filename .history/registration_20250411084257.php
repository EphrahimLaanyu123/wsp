<?php
$host = '127.0.0.1';
$dbname = "wsp"; // change to your database name
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Collect and sanitize form data
    $patientId = $_POST['patientId']; // Ensure this is capturing the value
    $firstName = $_POST['firstName'];
    $surname = $_POST['surname'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $gender = $_POST['gender'];
    $county = $_POST['county'];

    $nokFirstName = $_POST['nokFirstName'];
    $nokSurname = $_POST['nokSurname'];
    $nokRelationship = $_POST['nokRelationship'];

    // Check if patient_id already exists in the patients table
    $patientId = $_POST['patientId'];
    $checkPatient = $conn->prepare("SELECT COUNT(*) FROM patients WHERE patient_id = :patient_id");
    $checkPatient->execute([':patient_id' => $patientId]);

    if ($checkPatient->fetchColumn() > 0) {
        echo "❌ Patient with ID '$patientId' already exists.";
        exit;
    }

    // Insert patient data (without patient_id)
    $stmt = $conn->prepare("INSERT INTO patients (first_name, surname, date_of_birth, gender, county)
                            VALUES (:first_name, :surname, :date_of_birth, :gender, :county)");

    $stmt->execute([
        ':first_name' => $firstName,
        ':surname' => $surname,
        ':date_of_birth' => $dateOfBirth,
        ':gender' => $gender,
        ':county' => $county
    ]);

    // Get the generated patient_id (auto-incremented value)
    $patientId = $conn->lastInsertId();

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
