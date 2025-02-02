<?php
session_start();
require_once('../models/dbmodel.php');
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['userid'];
    $event_id = $_POST['event_id'];
    $data = [
        'event_id' => $event_id,
        'user_id' => $user_id
    ];
    $register = registerAttendee($data);
    if ($register === true) {
        header("Location: ../views/Dashboard.php?event_id=$event_id");
        echo "<script>alert('Registration successful');</script>";
    }  else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again later']);
    }

    // $attendees = getAttendeesByEvent($event_id);
    // header('Content-Type: text/csv');
    // header('Content-Disposition: attachment; filename="attendees.csv"');
    // $output = fopen('php://output', 'w');
    // fputcsv($output, ['Attendee ID', 'User ID', 'Registration Date']);
    // foreach ($attendees as $row) {
    //     fputcsv($output, $row);
    // }
    // fclose($output);
}
} catch (Exception $e) {
    echo "An error occurred. Please try again later.";
}
