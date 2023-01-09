<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/styles.css">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
	<script type="text/javascript" src="../scripts/script.js"></script>
	<title>MEDILOG</title>
	
</head>

<body onload="resizeMenu()">
	<?php
	spl_autoload_extensions(".php");
	spl_autoload_register();
	use yasmf\HttpHelper;
	?>
	<div class="container-fluid h-100  text-white">
		<div class="row h-100">
			<!-- Menu -->
			<div id="menu" class="pt-3 menu col-md-1 col-3 col-sm-2 d-md-flex d-none flex-column gap-3 blue h-100 align-items-center">
				<span onclick="manageClass('menu','d-none')"class="material-symbols-outlined d-md-none d-sm-block text-end w-100">arrow_back</span>
				<div class=" green border-1 ratio ratio-1x1">

				</div>
				<a href="index.php?controller=medicamentslist" class=" green border-1 ratio ratio-1x1">

					<span class="d-flex display-1 align-items-center justify-content-center material-symbols-outlined">
						medication
					</span>

				</a>
				<a href="index.php?controller=patientslist" class=" green border-1 ratio ratio-1x1">
					<span class="d-flex justify-content-center align-items-center material-symbols-outlined">
						groups
					</span>
				</a>
			</div>
			<!-- Main page -->
			<div class="col-md-11 h-75 text-center">
				<!-- Bandeau outils -->	
				
				<nav class="  row h-15 navbar navbar-expand-lg navbar-light green">
					<div class="d-flex justify-content-between px-5 container-fluid green">
						
						<span class="h1 d-md-block d-none"> Visite n° </span>
						<div class="d-flex align-items-center">
							<!-- Barre de recherche -->
							<div class="d-flex me-2 py-2 px-3 bg-white border-1">
								<input type="search" placeholder="Mots clef" aria-label="Search">
								<span class="material-symbols-outlined text-black"> search </span>

							</div>

							<!-- Filtre -->
							<div class="dropdown green">
								<span class="p-3 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
									Filtres
								</span>
								<div class="p-0  dropdown-menu dropdown-menu-end green text-white no-border" aria-labelledby="dropdownMenuButton1">
									<form action="index.php" action="POST" class="d-flex flex-column green p-4">
										<input type="hidden" name="controller" value="medicamentslist">
										<table class="text-white ">
											
										</table>

										<input type="submit" value="Rechercher" >
									</div>
								</form>
							</div>

						</div>				

					</div>
				</nav>
				<!-- Bandeau Patient -->
				<div class="blue row">
					<div class="d-flex justify-content-between">
						<span></span>
						<div><?php echo $patient[0]['nom'] . " " . $patient[0]['prenom']?></div>
						<div>dqs</div>
					</div>
				</div>

				<div class="row">
					<div class="d-flex flex-row justify-content-between text-green">
						
						<span>Motif : <?php echo $visite[0]['motifVisite'] ; ?></span>
						<span>Date :  <?php echo $visite[0]['dateVisite']?></span>

						
					</div>
					<div class="d-flex flex-column justify-content-between text-green">
						
						Description
						<textarea>
							<?php echo $visite[0]['Description'] ?>
						</textarea>
						Conclusion
						<textarea>
							<?php echo $visite[0]['Conclusion'] ?>
						</textarea>
					</div>
					<div class="d-flex flex-row justify-content-between text-green">
						
						<input type="submit" value="Annuler">
						<input type="submit" value="Valider">
					</div>
				</div>

				<!-- content -->
				<div class="row align-items-center text-center">
					<!-- Portail de connexion -->
					<div class="container ">
						<div class="row justify-content-center">

							<div class="overflow-scroll h-50 col-md-10 col-xl-9 col-sm-7 col-12 green border-2 p-5">
								<table class="">
									<tr>
										<th>codeCIS</th>
										<th>Nom</th>
										<th>Substance Active</th>
									</tr>

									<?php
									

										foreach ($drugs as $row) {

										echo "<tr>"
											 ."<td>" . $row['codeCIS'] . "</td>"

											 ."</tr>";
										} 
									?>
								</table>

							</div>


						</div>

					</div>
				</div>

				<div class="d-flex flex-row justify-content-end">
					<div class="d-flex me-2 py-2 px-3 border-1 green">
						Ajouter un medicament
					</div>
					<div class="d-flex me-2 py-2 px-3 border-1 green">
						Modifier la visite
					</div>
				</div>
				
				
			</div>

		</div>


		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>
