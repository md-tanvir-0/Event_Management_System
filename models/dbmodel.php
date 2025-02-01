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
            return 'Email already used';
        } else {
            $email_check->close();
            $conn->begin_transaction();
            $stmt1 = $conn->prepare("INSERT INTO user_reg (full_name, phone, email) VALUES (?, ?, ?)");
            $stmt1->bind_param("sss", $fullName, $phone, $email);

            if (!$stmt1->execute()) {
                throw new Exception("Failed to insert into registration table");
            }

            $userId = $conn->insert_id;
            $stmt2 = $conn->prepare("INSERT INTO user_login (user_id, username, password_hash) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $userId, $email, $hashedPassword);

            if (!$stmt2->execute()) {
                throw new Exception("Failed to insert into login table");
            }

            $conn->commit();
            $stmt1->close();
            $stmt2->close();
            $conn->close();
            return true;
        }
    } catch (Exception $e) {
        error_log("Error during registration: " . $e->getMessage());

        if (isset($stmt1)) $stmt1->close();
        if (isset($stmt2)) $stmt2->close();
        if (isset($email_check)) $email_check->close();
        $conn->close();

        return false;
    }
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
        return false;
    }
}
function createEvent($data) {
    $conn = getConnection();
    if (!$conn) {
        error_log("Database connection failed");
        return false;
    }
    $sql = "INSERT INTO event_s (user_id, event_name, description, event_date, venue, max_capacity, registration_deadline)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    $stmt->bind_param("issssis", 
        $data['user_id'], 
        $data['event_name'], 
        $data['description'], 
        $data['event_date'], 
        $data['venue'], 
        $data['max_capacity'], 
        $data['registration_deadline']
    );
    $result = $stmt->execute();
    if (!$result) {
        error_log("Execute failed: " . $stmt->error);
    }
    return $result;
}

function getAllByUser($userId) {
    $conn = getConnection();
    $sql = "SELECT * FROM event_s WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateEvent($eventId, $data) {
    $conn = getConnection();
    $sql = "UPDATE event_s SET event_name = ?, description = ?, event_date = ?, venue = ?, max_capacity = ?, registration_deadline = ?
            WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiis", $data['event_name'], $data['description'], $data['event_date'], $data['venue'], $data['max_capacity'], $data['registration_deadline'], $eventId);
    return $stmt->execute();
}

function deleteEvent($eventId) {
    $conn = getConnection();
    $sql = "DELETE FROM event_s WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    return $stmt->execute();
}
function registerAttendee($data) {
    $conn = getConnection();
    $sql = "INSERT INTO attendees (event_id, user_id)
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $data['event_id'], $data['user_id']);
    return $stmt->execute();
}

function getAttendeesByEvent($eventId) {
    $conn = getConnection();
    $sql = "SELECT attendees.attendee_id, user_reg.name AS attendee_name, attendees.registration_date
            FROM attendees
            JOIN user_reg ON attendees.user_id = user_reg.user_id
            WHERE attendees.event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAttendeeCountByEvent($eventId) {
    $conn = getConnection();
    $sql = "SELECT COUNT(*) AS attendee_count
            FROM attendees
            WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['attendee_count'];
}

function getFullName($userId)
{
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT full_name FROM user_reg WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fullName);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        return $fullName;
    } catch (Exception $e) {
        error_log("Error during fetching user name: " . $e->getMessage());
        return false;
    }
}
?>