<?php
$host = '127.0.0.1';
$dbname = "wsp"; // your actual database name
$username = "root";
$password = "";  // default password for XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sanitize form inputs
    $patientId = htmlspecialchars($_POST['patientId']);
    $firstName = htmlspecialchars($_POST['firstName']);
    $surname = htmlspecialchars($_POST['surname']);
    $dateOfBirth = htmlspecialchars($_POST['dateOfBirth']);
    $gender = htmlspecialchars($_POST['gender']);
    $county = htmlspecialchars($_POST['county']);

    $nokFirstName = htmlspecialchars($_POST['nokFirstName']);
    $nokSurname = htmlspecialchars($_POST['nokSurname']);
    $nokRelationship = htmlspecialchars($_POST['nokRelationship']);

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

    echo "✅ Patient registration successful.";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
