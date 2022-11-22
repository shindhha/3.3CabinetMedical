<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/styles.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
	<script type="text/javascript" src="../scripts/script.js"></script>
	<title>MEDILOG</title>
	<?php
		$hostname = "sql.alphaline.ml";
		$dbname = "SAE";
		$user = "guillaume";
		$password = "guillaume";
	?>
</head>

<body onload="resizeMenu()">
	<div class="container-fluid h-100  text-white">
		<div class="row h-100">
			<!-- Menu -->
			<div id="menu" class="menu col-md-1 col-3 col-sm-2 d-md-flex d-none flex-column gap-3 blue h-100 align-items-center">
				<span onclick="manageClass('menu','d-none')"class="material-symbols-outlined d-md-none d-sm-block text-end w-100">arrow_back</span>
				<div class=" green align-items-center text-center border-1 ratio ratio-1x1">

				</div>
				<div class=" green align-items-center text-center border-1 ratio ratio-1x1">

				</div>
				<div class=" green align-items-center text-center border-1 ratio ratio-1x1">

				</div>
			</div>
			<!-- Main page -->
			<div class="col-md-11 h-75 text-center">
				<!-- Bandeau outils -->

				<div class="row h-15 green">
					<div class="d-flex justify-content-between align-items-center px-5">
						<img onclick="manageClass('menu','d-none')" class="d-md-none d-sm-block sizeIcon" src="res/menu.svg">
						<span class="fs-1 d-md-block d-none"> Liste Patients </span>
						<div class="d-flex flex-row align-items-center gap-4">
							<div class="d-flex border-1 bg-white px-2 py-2">
								<input name="keyWords" type="text" placeholder="Mots clefs" class="border-0 ">
								<span class="material-symbols-outlined text-dark pointer">search</span>

							</div>
							 <div class="d-flex">
								 <span> Filtres </span>
								 <span  onclick="manageClass('param','d-none')" class="material-symbols-outlined pointer"> expand_more </span>
								 <div id="param" class="bg-white d-none position-absolute bottom-75"> dqsdqsdqs</div>
							 </div>
							 <span class="material-symbols-outlined pointer"> autorenew </span>
						</div>

					</div>


				</div>
				<span class="fs-1 d-md-none d-sm-block text-green"> Liste Patients </span>
				<!-- content -->
				<div class="row h-100 align-items-center text-center">

					<div class="container ">
						<div class="row justify-content-center">
							<div class="col-md-8 col-xl-6 col-sm-7 col-12 green border-2 p-5">
								<table>

								</table>
							</div>

						</div>

					</div>
				</div>
			</div>

		</div>



	</div>
</body>
</html>
