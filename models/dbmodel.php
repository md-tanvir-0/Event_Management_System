<?php
require_once('db.php');

function RegistrationData($fullName, $phone, $email, $hashedPassword)
{

    try {
        $conn = getConnection();
        $email_check = $conn->prepare("SELECT email FROM user_reg WHERE email = ?");
        $email_check->bind_param("s", $email);
        $email_check->execute();
        $email_check->store_result();

        if ($email_check->num_rows > 0) {
            $email_check->close();
            $conn->close();
            return 'Email already used';
        }
        else{
            $stmt1 = $conn->prepare("INSERT INTO user_reg (full_name, phone, email) VALUES (?, ?, ?)");
            $stmt1->bind_param("sss", $fullName, $phone, $email);
    
            if ($stmt1->execute()) {
                $userId = $conn->insert_id;
    
                $stmt2 = $conn->prepare("INSERT INTO user_login (user_id, username, password_hash) VALUES (?, ?, ?)");
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
        }
       
    } catch (Exception $e) {
        error_log("Error during registration: " . $e->getMessage());
    }

    //return false;
}

function LoginData($email, $password)
{
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT user_id, password_hash FROM user_login WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $hashedPassword);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            if (password_verify($password, $hashedPassword)) {
                $stmt->close();
                $conn->close();
                return $userId;
            } else {
                $stmt->close();
                $conn->close();
                return 'Invalid Password';
            }
        } else {
            $stmt->close();
            $conn->close();
            return 'Invalid Email';
        }
    } catch (Exception $e) {
        error_log("Error during login: " . $e->getMessage());
    }

    //return false;
}
?>
