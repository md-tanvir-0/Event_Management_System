<?php
require_once('../controllers/eventController.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="./Dashboard.php">EMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#createEventModal">Create Event</button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" style="z-index: 1050 !important;">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="./MyEvents.php">My Events</a></li>
                            <li><a class="dropdown-item" href="../controllers/logoutController.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventLabel">Create Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="createEventForm">
                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" class="form-control" name="event_name1" id="event_name1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Venue</label>
                            <input type="text" class="form-control" name="venue1" id="venue1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Capacity</label>
                            <input type="text" class="form-control" name="max1" id="max1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" class="form-control" name="date1" id="date1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Registration Deadline</label>
                            <input type="date" class="form-control" name="deadline1" id="deadline1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description1" id="description1" required></textarea>
                        </div>
                        <input type="hidden" name="action" value="create_event">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>