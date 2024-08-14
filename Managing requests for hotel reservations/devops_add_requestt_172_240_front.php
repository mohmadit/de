<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hotel Room Booking</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
background-color: #f8f9fa;
}
.container {
margin-top: 50px;
}
#response-message {
display: none;
}
</style>
</head>
<body>
<div class="container">
<h2>Hotel Room Booking</h2>
<form id="booking-form">
<div class="form-group">
<label for="room_number">Room Number</label>
<input type="text" class="form-control" id="room_number" name="room_number" required>
</div>
<div class="form-group">
<label for="booking_date">Booking Date</label>
<input type="date" class="form-control" id="booking_date" name="booking_date" required>
</div>
<button type="submit" class="btn btn-primary">Book Room</button>
</form>
<div id="response-message" class="mt-3 alert"></div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
$('#booking-form').on('submit', function(event) {
event.preventDefault();
$.ajax({
url: 'devops_process_add_request_171_239_backend.php',
type: 'POST',
data: $(this).serialize(),
success: function(response) {
$('#response-message').removeClass('alert-success alert-danger');
$('#response-message').addClass(response.includes('successful') ? 'alert-success' : 'alert-danger');
$('#response-message').text(response).show();


setTimeout(function() {
window.location.href = 'devops_add_requestt_172_240_front.php';
}, 3000);
}
});
});
});
</script>
</body>
</html>