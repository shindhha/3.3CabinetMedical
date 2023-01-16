<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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
					<span class="d-flex display-1 justify-content-center align-items-center material-symbols-outlined font-40">
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
						<h1><?php echo $patient['nom'] . " " . $patient['prenom']?></h1>
						<div>
							<?php 
							if ($patient['sexe']) {
								echo "<span class='material-symbols-outlined display-3 font-40' >
										man
									</span>";
							} else {
								echo "<span class='material-symbols-outlined display-3 font-40'>
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
									<div> Adresse : </div> <div> <?php echo $patient['adresse'] ?></div>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>n°Telephone : </div><?php echo "0".$patient['numTel'] ?>	
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>email : </div><?php echo $patient['email'] ?>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Medecin Traitant : </div><?php echo $patient['medecinRef'] != 0 ? $patient['medecinRef'] : "Non définie"; ?>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Numéro de sécurité sociale : </div><?php echo $patient['numSecu'] ?>
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Date de naissance : </div><?php echo $patient['dateNaissance'] ?>		
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>Lieu de naissance : </div><?php echo $patient['LieuNaissance'] ?>		
								</div>
								<div class="d-flex flex-row justify-content-between">
									<div>CodePostal : </div><?php echo $patient['codePostal'] ?>
								</div>
							</div>
							
						</div>

						<div class="d-flex flex-column col-xl-3 text-start">
							<div class="h2">Notes</div>
							<div class="d-flex align-items-start border border-dark ratio ratio-21x9">
								<?php echo $patient['notes'] ?>
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
							<div class=" col-md-10 col-xl-12 col-sm-7 col-12 success border-2 p-5">
								<div class="text-dark text-start h2">Liste des visites</div>
								<div class="overflow-scroll h-50">
									<table class="table table-striped lightGreen border-top border-dark">
										<thead class="sticky-top green">
											<tr>
												<th>Motif</th>
												<th>Date</th>
												<th>Description</th>
												<th></th>
											</tr>
										</thead>
										
										<?php
										foreach ($visites as $row) {
										echo "<tr>"
												 ."<td>" . $row['motifVisite'] . "</td>"
												 ."<td>" . $row['dateVisite'] . "</td>"
												 ."<td>" . $row['Description'] . "</td>"
										?>
										<td>
										<div class="dropdown">
											<span class="material-symbols-outlined" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
												more_horiz
											</span>
											<div class="p-0  dropdown-menu dropdown-menu-end green text-white no-border" aria-labelledby="dropdownMenuButton1">
												<table class="text-white ">
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="controller" value="patientslist">
														<input type="hidden" name="action" value="goFicheVisite">
														<input type="hidden" name="idVisite" value="<?php echo $row['idVisite'] ?>">
														<tr><td><input type="submit" name="modif" value="Afficher"> </td></tr>
													</form>
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="idVisite" value="<?php echo $row['idVisite'] ?>">
														<tr><td><a  href="#exampleModal" data-bs-toggle="modal" class="btn green" name="modif" onclick="add('<?php echo "Visite : " .$row['motifVisite']. " de " . $patient['nom'] . " " . $patient['prenom'] . "','". $row['idVisite']  ?>')">Supprimer</a> </td></tr>
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
								<div class="d-flex flex-row justify-content-end">
									<div class="d-flex me-2 py-2 px-3 border-1 green">
										<form>
											<input type="hidden" name="nextAction" value="addVisite">
											<input type="hidden" name="action" value="goEditVisite">
											<input type="hidden" name="controller" value="patientslist">
											<input type="submit" class="green no-border text-white" value="Ajouter une visite">
										</form>
									</div>
									<div class="d-flex me-2 py-2 px-3 border-1 green">
										<form>
											<input type="hidden" name="nextAction" value="updatePatient">
											<input type="hidden" name="action" value="goEditPatient">
											<input type="hidden" name="controller" value="patientslist">
											<input type="submit" class="green no-border text-white" value="Modifier le patient">
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		 		<div class="modal-dialog modal-md modal-dialog-centered">

		    		<div class="modal-content ">
		    			<div class = "h5 col-12 green d-flex text-start p-3 align-middle">
		    				<span id ="libelle"></span>
		    			</div>
		    			<div class="text-center text-danger d-flex flex-column">
		    				<span>Etes vous sur de vouloir supprimer la visite ?</span>
		    				<span>Touts ses médicaments seront perdue .</span>
		    			</div>
		    			<div class = "d-flex justify-content-end p-3 gap-3">
		    				<input type="submit" class="green no-border text-white me-2 py-2 px-3 border-1" data-bs-dismiss="modal" value="Annuler">
		    				<form>		
		    					<input type="submit" class="green no-border text-white me-2 py-2 px-3 border-1" value="confirmer">
								<input type="hidden" name="controller" value="patientslist">
								<input type="hidden" name="action" value="deleteVisite">
		    					<input type="hidden" name="idVisite" value="" id ="code">
		    					<input type="hidden" name="idPatient" value="<?php echo $_SESSION['patient'] ?>">
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
