<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .booking-form {
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .main-container {
            max-width: 600px;
            margin: auto;
        }
        .result-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container main-container">
    <h1 class="text-center">Hotel Booking</h1>
    <div class="text-center">
        <button id="bookBtn" class="btn btn-primary">Book a Room</button>
    </div>

    <div id="bookingForm" class="booking-form mt-4 d-none">
        <form id="reservationForm" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="checkIn" class="form-label">Check-In Date</label>
                <input type="date" class="form-control" id="checkIn" name="check_in" required>
            </div>
            <div class="mb-3">
                <label for="checkOut" class="form-label">Check-Out Date</label>
                <input type="date" class="form-control" id="checkOut" name="check_out" required>
            </div>
            <div class="mb-3">
                <label for="persons" class="form-label">Number of Persons</label>
                <input type="number" class="form-control" id="persons" name="persons" required>
            </div>
            <div class="mb-3">
                <label for="rooms" class="form-label">Number of Rooms</label>
                <input type="number" class="form-control" id="rooms" name="rooms" required>
            </div>
            <div class="mb-3">
                <label for="roomType" class="form-label">Room Type</label>
                <select class="form-select" id="roomType" name="room_type" required>
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Reserve Now</button>
        </form>
        <div id="resultMessage" class="result-message d-none"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bookBtn').click(function() {
            $('#bookingForm').toggleClass('d-none');
        });

        $('#reservationForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'devops_add_process_182_248_backend.php',
                data: $(this).serialize(),
                success: function(response) {
                    $('#resultMessage').removeClass('d-none').html(response);
                }
            });
        });
    });
</script>

</body>
</html>
