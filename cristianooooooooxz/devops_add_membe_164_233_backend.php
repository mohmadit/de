<?phpgfchvhbj,n.
session_start()
require 'config.php'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];
$name = $_POST['name'];

$sql = "SELECT id, name, password, role, first_login FROM users WHERE email = ? AND role = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $name_db, $hashed_password, $role_db, $first_login);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
$_SESSION['user_id'] = $id;
$_SESSION['user_name'] = $name_db;
$_SESSION['user_role'] = $role_db;

if ($first_login) {
header("Location: change_password.php");
} else {
header("Location: dashboard.php");
}
} else {
$error = "Invalid email, password or role";
}

$stmt->close();
}
?>