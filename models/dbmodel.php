<?php
require_once('db.php');

function RegistrationData($fullName, $phone, $email, $hashedPassword)
{

    try {
        $conn = getConnection();
        $stmt1 = $conn->prepare("INSERT INTO registration (full_name, email, phone) VALUES (?, ?, ?)");
        $stmt1->bind_param("sss", $fullName, $email, $phone);

        if ($stmt1->execute()) {
            $userId = $conn->insert_id;

            $stmt2 = $conn->prepare("INSERT INTO login (user_id, username, password_hash) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $userId, $email, $hashedPassword);

            if ($stmt2->execute()) {
                $stmt1->close();
                $stmt2->close();
                $conn->close();
                return true;
            } else {
                error_log("Failed to insert into login table: " . $stmt2->error);
                echo "<script>alert('Failed to insert into login table');</script>";
            }
        } else {
            error_log("Failed to insert into registration table: " . $stmt1->error);
            echo "<script>alert('Failed to insert into registration table');</script>";
        }
    } catch (Exception $e) {
        error_log("Error during registration: " . $e->getMessage());
    }

    //return false;
}
?>
