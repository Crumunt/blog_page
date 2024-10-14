<!DOCTYPE html>
<html>
	<head>
		<title>The Owl's Lounge</title>
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
		<script src="../bootstrap/js/bootstrap.js"></script>
	</head>
	<body style="background-color: #F9F9F9 !important;">
		<div class="container mt-5 h-100 bg-light">
			<div class="logo-container mx-auto border rounded-circle shadow bg-light" style="width: fit-content;">
				<img src="../assets/logo.png" alt="" style="width: 100px;">
			</div>
			<h1 class="text-center fw-bolder mt-3 text-capitalize">The Owl's Lounge</h1>
			<form action="loginHandler.php" method="POST" class="w-50 mx-auto mt-5 border p-3 shadow-sm rounded-4">
				<div class="form-group mb-3">
					<label for="" class="form-label">Username</label>
					<input type="text" name="email" id="" class="form-control" required>
				</div>
				<div class="form-group mb-3">
					<label for="" class="form-label">Password</label>
					<input type="password" name="password" id="" class="form-control" required>
				</div>
				<div class="button-group d-grid gap-2">
					<button type="submit" name="login" class="btn text-white" style="background-color: #988F88;">Login</button>
					<a href="../index.php" class="btn text-white" style="background-color: #30393E;">Go Back</a>
				</div>
			</form>
		</div>
	</body>
</html>