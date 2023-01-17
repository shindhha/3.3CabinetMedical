<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
	<title>MEDILOG</title>
	<?php
	spl_autoload_extensions(".php");
	spl_autoload_register();
	use yasmf\HttpHelper;
	?>
</head>

<body onload="resizeMenu()">
	<div class="container-fluid h-100  text-white">
		<div class="row h-100">
			<!-- Menu -->
			<div id="menu" class="pt-3 menu z-index-dropdown col-md-1 col-4 d-md-flex d-none flex-column gap-3 blue h-100 align-items-center">
				<span onclick="manageClass('menu','d-none')"class="material-symbols-outlined d-block d-md-none text-end w-100">arrow_back</span>
				<div class=" green border-1 ratio ratio-1x1">

				</div>
				<a href="index.php?controller=administrateur" class="d-md-none">
					<div class="text-white green border-1 ratio ratio-1x1">
						<span class="d-flex display-3 text-white align-items-center justify-content-center material-symbols-outlined">
							article
						</span>
					</div>
				</a>
				<a href="index.php?controller=administrateur&action=goListMedecins" class="d-md-none">
					<div  class=" text-white green border-1 ratio ratio-1x1">
						<span class="d-flex display-3 text-white justify-content-center align-items-center material-symbols-outlined">
							groups
						</span>
					</div>
				</a>
				<a href="index.php?controller=administrateur" class="text-white d-none d-md-block green border-1 ratio ratio-1x1">

                    <span class="d-flex display-3 align-items-center justify-content-center material-symbols-outlined">
                        article
                    </span>
                </a>
                <a href="index.php?controller=administrateur&action=goListMedecins" class="text-white d-none d-md-block green border-1 ratio ratio-1x1">
                    <span class="d-flex display-3 justify-content-center align-items-center material-symbols-outlined">
                        groups
                    </span>
                </a>
                <a href="index.php?controller=administrateur&action=goErreursImport" class="text-white d-none d-md-block green border-1 ratio ratio-1x1">
                    <span class="d-flex display-3 align-items-center justify-content-center material-symbols-outlined">
                        settings
                    </span>
                </a>
                <a href="index.php?controller=administrateur&action=goErreursImport" class="d-md-none">
					<div  class=" text-white green border-1 ratio ratio-1x1">
						<span class="d-flex display-3 text-white justify-content-center align-items-center material-symbols-outlined">
							settings
						</span>
					</div>
				</a>
			</div>
			<!-- Main page -->
			<div class="col-md-11 h-75 text-center">
				<!-- Bandeau outils -->	
				
				<nav class="  row h-15 navbar navbar-expand-lg navbar-light green">
					<div class="d-flex justify-content-between px-5 container-fluid green">
						<span class="h1 d-md-block d-none"> Administrateur </span>
                        <form action="index.php" method="POST">
                            <input type="hidden" id="controller" value="administrateur">
                            <input type="hidden" id="action" value="importAll">
							<span class="material-symbols-outlined text-start d-block d-md-none" onclick="manageClass('menu','d-none')">menu</span>
							<form>
								<input type="hidden" name="action" value="deconnexion">
								<input type="submit" class="btn btn-danger" value="Deconnexion">
							</form>
							
                        </form>
                    </div>
				</nav>
				
				<span class="fs-1 d-md-none d-sm-block text-green"> Administrateur </span>
				<!-- content -->
				<div class="row h-100 align-items-center text-center">
					<!-- Portail de connexion -->
					<div class="container">
						<div class="row justify-content-center text-start">
                            <span class="h1 text-dark">Informations du cabinet</span>
							<form class="form-control">
                                <label for="adresse">Adresse : </label>
                                <input class="form-control" type="text" name="adresse" value="<?php if (isset($cabinet['adresse'])) echo $cabinet['adresse']; ?>">
                                <label for="codePostal">Code postal : </label>
                                <input class="form-control" type="number" name="codePostal" min="1001" max="98800" value="<?php if (isset($cabinet['codePostal'])) echo $cabinet['codePostal']; ?>">
                                <label for="ville">Ville : </label>
                                <input class="form-control" type="text" name="ville" value="<?php if (isset($cabinet['ville'])) echo $cabinet['ville']; ?>">
								<input type="hidden" name="controller" value="administrateur">
								<input type="hidden" name="action" value="insertCabinet">
                                <div class="text-center mt-2">
                                    <input type="submit" value="Inserer">
                                </div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>

		<script type="text/javascript" src="scripts/script.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>
