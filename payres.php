


<!DOCTYPE html>
<html>
<head>
	<title>Booking successful</title>
	<style>
		.container {
			margin: 50px auto;
			padding: 20px;
			text-align: center;
			background-color: #F2F2F2;
			border: 1px solid #ddd;
			border-radius: 5px;
			max-width: 500px;
		}
		.btn {
			padding: 10px 20px;
			background-color: #4CAF50;
			color: #fff;
			border: none;
			border-radius: 3px;
			cursor: pointer;
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>Booking successful</h1>
		<p>Your booking has been confirmed.</p>
		<button class="btn" onclick="backto()">Okay</button>
	</div>
</body>
<script>
	function backto(){
		window.location="index.php";
		alert('Check for your booking under booking tab in your profile');
	}
</script>
</html>








