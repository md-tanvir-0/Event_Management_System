<?php
session_start();
require_once('../models/dbmodel.php');
//require_once('../views/Login.php');
require_once('../controllers/loginController.php');
if (!isset($_SESSION['userid'])) {
    header("Location: ../views/Login.php");
    exit();
}
$userId = $_SESSION['userid'];
$fullName = getFullName($userId);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate user is logged in
        if (!isset($_SESSION['userid'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            exit();
        }

        // Event Creation Logic
       
            $event_name = $_POST['event_name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            // Basic validation
            if (empty($event_name) || empty($description)) {
                echo json_encode(['status' => 'error', 'message' => 'Event name and description are required']);
                exit();
            }

            $data = [
                'user_id' => $userId,
                'event_name' => $event_name,
                'description' => $description,
                'event_date' => date('Y-m-d H:i:s'), // Default for now
                'venue' => 'TBD', // Default placeholder
                'max_capacity' => 100, // Default placeholder
                'registration_deadline' => date('Y-m-d', strtotime('+7 days')) // Default
            ];

            if (createEvent($data)) {
                header("Location: ../views/Dashboard.php");
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Could not create event']);
            }
            exit();
        
        if (isset($_POST['action']) && $_POST['action'] === 'update_event') {
            //$event_id = $_POST['event_id'] ?? null;
            $event_name = $_POST['event_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $event_date = $_POST['event_date'] ?? null;
            $venue = $_POST['venue'] ?? '';
            $max_capacity = $_POST['max_capacity'] ?? 100;
            $registration_deadline = $_POST['registration_deadline'] ?? null;

            $data = [
                'event_name' => $event_name,
                'description' => $description,
                'event_date' => $event_date,
                'venue' => $venue,
                'max_capacity' => $max_capacity,
                'registration_deadline' => $registration_deadline
            ];

            if (updateEvent($event_id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Event updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Could not update event']);
            }
            exit();
        }
    }
} catch (Exception $e) {
    error_log("Event Controller Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred']);
    exit();
}
?>