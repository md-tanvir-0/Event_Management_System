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

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        if ($_GET['action'] === 'get_event') {
            $event_id = $_GET['event_id'] ?? null;
            
            if (!$event_id) {
                echo json_encode(['status' => 'error', 'message' => 'Event ID is required']);
                exit();
            }

            // Assuming you have a function to get event by ID
            $event = getEventById($event_id);
            
            if ($event) {
                // Return event data as JSON
                echo json_encode([
                    'status' => 'success',
                    'event_id' => $event['event_id'],
                    'event_name' => $event['event_name'],
                    'description' => $event['description'],
                    'event_date' => $event['event_date'],
                    'venue' => $event['venue'],
                    'max_capacity' => $event['max_capacity'],
                    'registration_deadline' => $event['registration_deadline']
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Event not found']);
            }
            exit();
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate user is logged in
        if (!isset($_SESSION['userid'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            exit();
        }

        // Event Creation Logic
        if (isset($_POST['action']) && $_POST['action'] === 'create_event') {
            $event_name = $_POST['event_name1'] ?? '';
            $event_venue = $_POST['venue1'];
            $max = $_POST['max1'];
            $description = $_POST['description1'] ?? '';
            $event_date = $_POST['date1'];
            $deadline = $_POST['deadline1'];

            //file_put_contents('data.txt', $event_name, FILE_APPEND);
            
            // Basic validation
            if (empty($event_name) || empty($event_venue) || empty($max) || empty($description)) {
                echo json_encode(['status' => 'error', 'message' => 'Event name and description are required']);
                exit();
            }

            $data = [
                'user_id' => $userId,
                'event_name' => $event_name,
                'description' => $description,
                // 'event_date' => date('Y-m-d H:i:s'), // Default for now
                'event_date' => $event_date, // Default for now
                'venue' => $event_venue, // Default placeholder
                'max_capacity' => $max, // Default placeholder
                'registration_deadline' => $deadline // Default
            ];
            if (createEvent($data)) {
                header("Location: ../views/Dashboard.php");
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Could not create event']);
            }
            exit();
        }
            
        
            if (isset($_POST['action']) && $_POST['action'] === 'update_event') {
                $event_id = $_POST["event_id"];
                $event_name = $_POST["event_name"];
                $event_desc = $_POST["description"];
                $event_venue = $_POST['venue'];
                $max = $_POST['max'];
                $date = $_POST['date'];
                $deadline = $_POST['deadline'];

                $data = [
                    'event_name' => $event_name,
                    'description' => $event_desc,
                    'event_date' => $date,
                    'venue' => $event_venue,
                    'max_capacity' => $max,
                    'registration_deadline' => $deadline
                ];
                // file_put_contents('id.txt', $event_id, FILE_APPEND);
                //file_put_contents('data.txt', $data, FILE_APPEND);

                if (updateEvent($event_id, $data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Event updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update event']);
                }
                exit();
            }

            if (isset($_POST['action']) && $_POST['action'] === 'delete_event') {
                $event_id = $_POST['event_id'] ?? null;
                
                if (!$event_id) {
                    echo json_encode(['status' => 'error', 'message' => 'Event ID is required']);
                    exit();
                }
    
                // Verify that the event belongs to the current user
                $event = getEventById($event_id);
                if (!$event || $event['user_id'] != $userId) {
                    echo json_encode(['status' => 'error', 'message' => 'Event not found or unauthorized']);
                    exit();
                }
    
                if (deleteEvent($event_id)) {
                    echo json_encode(['status' => 'success', 'message' => 'Event deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete event']);
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