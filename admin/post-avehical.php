<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {

	if (isset($_POST['submit'])) {
		$vehicletitle = $_POST['vehicletitle'];
		$brand = $_POST['brandname'];
		$vehicleoverview = $_POST['vehicalorcview'];
		$priceperday = $_POST['priceperday'];
		$modelyear = $_POST['modelyear'];
		$seatingcapacity = $_POST['seatingcapacity'];
		$vimage1 = $_FILES["img1"]["name"];
		move_uploaded_file($_FILES["img1"]["tmp_name"], "img/vehicleimages/" . $_FILES["img1"]["name"]);

		$sql = "INSERT INTO tblvehicles(VehiclesTitle,VehiclesBrand,VehiclesOverview,PricePerDay,ModelYear,SeatingCapacity,Vimage1) VALUES(:vehicletitle,:brand,:vehicleoverview,:priceperday,:modelyear,:seatingcapacity,:vimage1)";
		$query = $dbh->prepare($sql);
		$query->bindParam(':vehicletitle', $vehicletitle, PDO::PARAM_STR);
		$query->bindParam(':brand', $brand, PDO::PARAM_STR);
		$query->bindParam(':vehicleoverview', $vehicleoverview, PDO::PARAM_STR);
		$query->bindParam(':priceperday', $priceperday, PDO::PARAM_STR);
		$query->bindParam(':modelyear', $modelyear, PDO::PARAM_STR);
		$query->bindParam(':seatingcapacity', $seatingcapacity, PDO::PARAM_STR);
		$query->bindParam(':vimage1', $vimage1, PDO::PARAM_STR);
		$query->execute();
		$lastInsertId = $dbh->lastInsertId();
		if ($lastInsertId) {
			$msg = "Vehicle posted successfully";
		} else {
			$error = "Something went wrong. Please try again";
		}
	}


?>
<!doctype html>
	<html lang="en" class="no-js">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="theme-color" content="#3e454c">

		<title>Car Rental Portal | Admin Post Vehicle</title>

		<!-- Font awesome -->
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<!-- Sandstone Bootstrap CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap Datatables -->
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
		<!-- Bootstrap social button library -->
		<link rel="stylesheet" href="css/bootstrap-social.css">
		<!-- Bootstrap select -->
		<link rel="stylesheet" href="css/bootstrap-select.css">
		<!-- Bootstrap file input -->
		<link rel="stylesheet" href="css/fileinput.min.css">
		<!-- Awesome Bootstrap checkbox -->
		<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
		<!-- Admin Stye -->
		<link rel="stylesheet" href="css/style.css">
		<style>
			.errorWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #dd3d36;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.succWrap {
				paudding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #5cb85c;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}
		</style>

	</head>

	<body>
<?php include('includes/header.php'); ?>
<div class="ts-main-content">
<?php include('includes/leftbar.php'); ?>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<h2 class="page-title">Post A Vehicle</h2>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading">Basic Info</div>
<?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
<?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>

<div class="panel-body">
	<form method="post" class="form-horizontal" enctype="multipart/form-data">
		<div class="form-group">
			<label class="col-sm-2 control-label">Vehicle Title<span style="color:red">*</span></label>
			<div class="col-sm-4">
				<input type="text" name="vehicletitle" class="form-control" required>
			</div>
			<label class="col-sm-2 control-label">Select Brand<span style="color:red">*</span></label>
			<div class="col-sm-4">
<select class="selectpicker" name="brandname" required>
	<option value=""> Select </option>
	<?php $ret = "select id,BrandName from tblbrands";
	$query = $dbh->prepare($ret);
	//$query->bindParam(':id',$id, PDO::PARAM_STR);
	$query->execute();
	$results = $query->fetchAll(PDO::FETCH_OBJ);
	if ($query->rowCount() > 0) {
		foreach ($results as $result) {
	?>
			<option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->BrandName); ?></option>
	<?php }
	} ?>

	</select>
	</div>
	</div>
	<div class="hr-dashed"></div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Price Per Day(in USD)<span style="color:red">*</span></label>
			<div class="col-sm-4">
				<input type="text" name="priceperday" class="form-control" required>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Model Year<span style="color:red">*</span></label>
				<div class="col-sm-4">
					<input type="text" name="modelyear" class="form-control" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Seating Capacity<span style="color:red">*</span></label>
				<div class="col-sm-4">
					<input type="text" name="seatingcapacity" class="form-control" required>
				</div>
			</div>
			</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<h4><b>Upload Images</b></h4>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-4">
					Image 1 <span style="color:red">*</span><input type="file" name="img1" required>
				</div>
			</div>
			<div class="hr-dashed"></div>
			</div>
			</div>
			</div>
			</div>
			</div>
			<div class="form-group">
			<div class="col-sm-8 col-sm-offset-2">
				<button class="btn btn-default" type="reset">Cancel</button>
				<button class="btn btn-primary" name="submit" type="submit">Save changes</button>
			</div>
			</div>
			</form>
			</div>
			</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>

		<!-- Loading Scripts -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap-select.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>
		<script src="js/Chart.min.js"></script>
		<script src="js/fileinput.js"></script>
		<script src="js/chartData.js"></script>
		<script src="js/main.js"></script>
	</body>

	</html>
<?php } ?>