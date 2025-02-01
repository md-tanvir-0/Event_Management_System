<?php
//require_once('../controllers/dashboardController.php');
require_once('../controllers/eventController.php');
require_once('./Navbar.php');
$eventsPerPage = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $eventsPerPage;
$totalEvents = count(getAllEvent()); // Get total event count
$totalPages = ceil($totalEvents / $eventsPerPage);
$events = getPaginatedEvents($offset, $eventsPerPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .event-carousel {
            width: 100%;
            height: 40vh;
            /* 40% of viewport height */
            overflow: hidden;
            /* Ensures no overflow issues */
        }

        .event-carousel .carousel-inner,
        .event-carousel .carousel-item {
            width: 100%;
            height: 100%;
        }

        .event-carousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensures the image covers the entire area */
        }
    </style>
</head>
</head>

<body>
    <!-- Navbar -->
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Event Management System</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#createEventModal">Create Event</button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="../controllers/logoutController.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->

    <!-- Hero Section -->
    <div id="eventCarousel" class="carousel slide event-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../public/img/event1.png" alt="Event 1" class="img-fluid">
                <div class="carousel-caption">
                    <h1>Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
                    <p>Manage and organize your events efficiently.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../public/img/event2.jpg" alt="Event 2" class="img-fluid">
                <div class="carousel-caption">
                    <h1>Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
                    <p>Manage and organize your events efficiently.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../public/img/event3.jpg" alt="Event 3" class="img-fluid">
                <div class="carousel-caption">
                    <h1>Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
                    <p>Manage and organize your events efficiently.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Search and Filter -->
    <div class="container my-4">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search events...">
            <select class="form-select">
                <option selected>Filter by Category</option>
                <option value="1">Conference</option>
                <option value="2">Workshop</option>
                <option value="3">Seminar</option>
            </select>
            <button class="btn btn-primary">Search</button>
        </div>
    </div>
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
                            <input type="text" class="form-control" name="event_name" id="event_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>
                        </div>
                        <input type="hidden" name="action" value="create_event">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row" id="eventList">
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
                            <p class="card-text"><small class="text-muted">Date: <?= htmlspecialchars($event['event_date']) ?></small></p>
                            <a href="#" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm">
                        <input type="hidden" name="action" value="update_event">
                        <input type="hidden" name="event_id" id="edit_event_id">
                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" class="form-control" name="event_name" id="edit_event_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"></div>
            </div>
        </div>
    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>">Next</a>
            </li>
        </ul>
    </nav>
    </div>
    <script>
        $('#createEventForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '../controllers/eventController.php',
                method: 'POST',
                data: $(this).serialize(),
                //dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Create a Bootstrap Toast with the success message
                        var toast = $('.toast');
                        $('.toast-body').text(response.message);
                        toast.toast('show');

                        // Reset the form after successful creation
                        $('#createEventForm')[0].reset();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.log(xhr.responseText);
                    alert('Network or server error occurred');
                }
            });
        });
        
    </script>
</body>

</html>