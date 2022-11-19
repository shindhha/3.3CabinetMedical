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

				<div class="row h-15 align-items-center green">
					<div class="d-flex">
						<img onclick="manageClass('menu','d-none')" class="d-md-none d-sm-block sizeIcon" src="res/menu.svg">
						<span class="fs-1 d-md-block d-none"> Page d'Accueil </span>
					</div>
					

				</div>
				<span class="fs-1 d-md-none d-sm-block text-green"> Page d'Accueil </span>
				<!-- content -->
				<div class="row h-100 align-items-center text-center">
					<!-- Portail de connexion -->
					<div class="container ">
						<div class="row justify-content-center">
							<div class="col-md-8 col-xl-6 col-sm-7 col-12 green border-2 p-5">
								<form class="d-flex flex-column gap-3">
									<span class="fs-1"> Connexion a <u>MEDILOG</u> </span>


									<div class="d-flex gap-3"><img src="res/iaccount.svg" class="sizeIcon"><input type="text" placeholder="Identifiant" class="border-0 border-1 w-75 ps-2 pt-2 pb-2"></div>

									<div class="d-flex gap-3"><img src="res/ipassword.svg" class="sizeIcon"><input type="password" placeholder="Mot de passe" class="ps-2 border-0 border-1 w-75 pt-2 pb-2"></input></div>

									<div><button class="border-0 w-50 border-1 text-green fs-3"><u> Valider </u></button></div>
									<a class="fs-6 text-white"> Mot de passe oublié </a>
								</form>
							</div>

						</div>

					</div>
				</div>
			</div>

		</div>



	</div>
</body>
</html>
