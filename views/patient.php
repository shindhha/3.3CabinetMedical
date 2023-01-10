<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/styles.css">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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

					<span class="d-flex display-1 align-items-center justify-content-center material-symbols-outlined font-40">
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
						
						<span class="h1 d-md-block d-none"> Fiche Patient </span>		

					</div>
				</nav>
				<!-- Bandeau Patient -->
				<div class="blue row">
					<div class="d-flex justify-content-between">
						<span></span>
						<h1><?php echo $patient[0]['nom'] . " " . $patient[0]['prenom']?></h1>
						<div>
							<?php 
							if ($patient[0]['sexe']) {
								echo "<span class='material-symbols-outlined font-40' >
										man
									</span>";
							} else {
								echo "<span class='material-symbols-outlined font-40'>
										woman
									</span>";
							}
							?>
							
						</div>
					</div>
				</div>

				<div class="row">
					<div class="d-flex  flex-row px-5 justify-content-between text-dark">
						<div class="d-flex flex-column col-xl-5 text-start">
							
							<div class="h2"> Informations </div>
							<div class="border-top border-dark pt-3	">
								
								<div class="d-flex flex-row justify-content-between">
									<div> Adresse : </div> <div> <?php echo $patient[0]['adresse'] ?></div>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>n°Telephone : </div><?php echo "0".$patient[0]['numTel'] ?>	
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>email : </div><?php echo $patient[0]['email'] ?>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Medecin Traitant : </div><?php echo $patient[0]['medecinRef'] ?>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Numéro de sécurité sociale : </div><?php echo $_SESSION['patient'] ?>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Date de naissance : </div><?php echo $patient[0]['dateNaissance'] ?>		
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Lieu de naissance : </div><?php echo $patient[0]['LieuNaissance'] ?>		
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>CodePostal : </div><?php echo $patient[0]['codePostal'] ?>
								</div>
							</div>
							
						</div>

						<div class="d-flex flex-column col-xl-3 text-start">
							<div class="h2">Notes</div>
							<div class="d-flex align-items-start border border-dark ratio ratio-21x9">
								<?php echo $patient[0]['notes'] ?>
							</div>
							
						</div>
					</div>
				</div>

				<span class="fs-1 d-md-none d-sm-block text-green"> Liste Patients </span>
				<!-- content -->
				<div class="row align-items-center text-center">

					<!-- Portail de connexion -->

					<div class="container ">

						<div class="row justify-content-center">

							<div class="overflow-scroll h-50 col-md-10 col-xl-12 col-sm-7 col-12 success border-2 p-5">

								<div class="text-dark text-start h2">Liste des visites</div>
								<table class="table table-striped lightGreen border-top border-dark">
									<tr>
										<th>Date</th>
										<th>Motif</th>
										<th>Description</th>
									</tr>
									<?php

									foreach ($visites as $row) {
									echo "<tr>"
											 ."<td>" . $row['motifVisite'] . "</td>"
											 ."<td>" . $row['dateVisite'] . "</td>"
											 ."<td>" . $row['Description'] . "</td>"
									?>
									<td>
										
									
									<div class="dropdown green">
										<span class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
									
											</span>
										<div class="p-0  dropdown-menu dropdown-menu-end green text-white no-border" aria-labelledby="dropdownMenuButton1">
											
												<table class="text-white ">
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="controller" value="patientslist">
														<input type="hidden" name="action" value="visite">
														<input type="hidden" name="idVisite" value="<?php echo $row['idVisite'] ?>">
														<tr><td><input type="submit" name="modif" value="Afficher"> </td></tr>
													</form>
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="controller" value="patientslist">
														<input type="hidden" name="action" value="fichePatient">
														<input type="hidden" name="idVisite" value="<?php echo $row['idVisite'] ?>">
														<input type="hidden" name="numSecu"
														value="<?php echo $_SESSION['patient'] ?>">
														<tr><td><input type="submit" name="modif" value="Supprimer"> </td></tr>
													</form>
												</table>

											
										</div>
									</div>

									</td>
									<?php
											echo "</tr>";
										}
									?>
									
								</table>

							</div>


						</div>

					</div>
				</div>
				<div class="d-flex flex-row justify-content-end">
					<div class="d-flex me-2 py-2 px-3 border-1 green">
						<form>
							<input type="hidden" name="modif" value="Ajouter">
							<input type="hidden" name="action" value="addVisite">
							<input type="hidden" name="controller" value="patientslist">
							<input type="submit" class="green no-border text-white" value="Ajouter une visite">
						</form>
					</div>
					<div class="d-flex me-2 py-2 px-3 border-1 green">
						<form>
							<input type="hidden" name="modif" value="Modifier">
							<input type="hidden" name="action" value="modifPatient">
							<input type="hidden" name="controller" value="patientslist">
							<input type="submit" class="green no-border text-white" value="Modifier le patient">
						</form>
					</div>
				</div>
			</div>

		</div>


		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>
