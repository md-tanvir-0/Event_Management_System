<?php
require_once('../controllers/eventController.php');
require_once('./Navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    
    <div class="container mt-5">
        <h1 class="text-center mb-4 sticky-top bg-white py-3 shadow-sm">My Events</h1>

        <div class="row justify-content-center" id="eventList">
            <?php
            $events = getAllByUser($_SESSION['userid']);
            foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm rounded-lg">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
                            <p class="card-text"><?= htmlspecialchars($event['attendee_count']) ?></p>
                            <p class="card-text">Venue: <?= htmlspecialchars($event['venue']) ?></p>
                            <p class="card-text">Max Capacity: <?= htmlspecialchars($event['max_capacity']) ?></p>
                            <p class="card-text">Registration Deadline: <?= htmlspecialchars($event['registration_deadline']) ?></p>
                            <p class="card-text text-muted"><small>Event Date: <?= htmlspecialchars($event['event_date']) ?></small></p>
                            
                            <div>
                                <button data-bs-toggle="modal" data-bs-target="#editEventModal" class="btn btn-warning" onclick="editEvent(<?= $event['event_id'] ?>)">
                                    Edit
                                </button>
                                <button class="btn btn-danger" onclick="deleteEvent(<?= $event['event_id'] ?>)">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>




    <!-- Edit Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <form method="post" action="../controllers/eventController.php" id="editEventForm">
                        <input type="hidden" name="action" value="update_event">
                        <input type="hidden" name="event_id" id="event_id">
                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" class="form-control" name="event_name" id="event_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Venue</label>
                            <input type="text" class="form-control" name="venue" id="venue" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Capacity</label>
                            <input type="text" class="form-control" name="max" id="max" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" class="form-control" name="date" id="date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Registration Deadline</label>
                            <input type="date" class="form-control" name="deadline" id="deadline" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>
                        </div>
                        <button type="submit" name="updateEvent" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Wait for document to be ready before running any jQuery code
    $(document).ready(function() {
        let currentEventData = null;

        // Function to handle edit event
        window.editEvent = function(eventId) {
            $.ajax({
                url: '../controllers/eventController.php',
                method: 'GET',
                data: {
                    action: 'get_event',
                    event_id: eventId
                },
                success: function(response) {
                    try {
                        console.log('Response:', response);
                        currentEventData = JSON.parse(response);
                        
                        if (currentEventData.status === 'success') {
                            populateModalFields(currentEventData);
                            $('#editEventModal').modal('show');
                        } else {
                            alert('Error: ' + currentEventData.message);
                        }
                    } catch (error) {
                        console.error('Error parsing event data:', error);
                        alert('Error loading event data. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Error loading event data. Please try again.');
                }
            });
        };

        function populateModalFields(eventData) {
            if (!eventData) return;
            
            $('#event_id').val(eventData.event_id);
            $('#event_name').val(eventData.event_name);
            $('#venue').val(eventData.venue);
            $('#max').val(eventData.max_capacity);
            $('#date').val(eventData.event_date);
            $('#deadline').val(eventData.registration_deadline);
            $('#description').val(eventData.description);
        }

        // Form submission handler
        $('#editEventForm').submit(function(e) {
            e.preventDefault();
            
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = submitButton.text();
            submitButton.prop('disabled', true).text('Updating...');
            
            $.ajax({
                url: '../controllers/eventController.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    try {
                        console.log('Update Response:', response);
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            $('#editEventModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Error: ' + (result.message || 'Unknown error occurred'));
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error);
                        alert('Error updating event. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Error updating event. Please try again.');
                },
                complete: function() {
                    submitButton.prop('disabled', false).text(originalText);
                }
            });
        });

        // Delete event handler
        window.deleteEvent = function(eventId) {
            if (confirm('Are you sure you want to delete this event?')) {
                $.post('../controllers/eventController.php', {
                    action: 'delete_event',
                    event_id: eventId
                }, function(response) {
                    let result = JSON.parse(response);
                    if (result.status === 'success') {
                        location.reload();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
            }
        };
    });
    function reloadEventsList() {
    $('#eventList').load(window.location.href + ' #eventList > *', function(response, status, xhr) {
        if (status === 'error') {
            console.error('Error reloading events:', xhr.statusText);
            alert('Error reloading events. Please refresh the page.');
        }
    });
}
    </script>
</body>
</html>