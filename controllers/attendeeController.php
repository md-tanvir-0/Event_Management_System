<?php
require_once('../models/dbmodel.php');
try {

    $user_id = $_SESSION['user_id'];
    if ($register = registerAttendee($event_id, $user_id)) {
        echo "Registration successful!";
    } else {
        echo "Event is full or you are already registered.";
    }


    $attendees = getAttendeesByEvent($event_id);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendees.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Attendee ID', 'User ID', 'Registration Date']);
    foreach ($attendees as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
} catch (Exception $e) {
    echo "An error occurred. Please try again later.";
}
