<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/styles.css">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

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
					<div class=" d-flex justify-content-between px-5 container-fluid green">
						
						<span class=" h1 d-md-block d-none"> Liste Patients </span>
						<div class="d-flex align-items-center">
							<!-- Barre de recherche -->
							<form action="index.php" action="POST" class="d-flex align-items-center">
								<div class="d-flex me-2 py-2 px-3 bg-white border-1">
									<input name="search" class="no-border" type="search" placeholder="Nom prenom" aria-label="Search">
									<input type="submit" class="no-border bg-white material-symbols-outlined text-black" value="search">  

								</div>

								<!-- Filtre -->
								<div class="dropdown green z-index-dropdown">
									<span class="p-3 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
									Filtres
									</span>
									<div class="p-0  dropdown-menu dropdown-menu-end  green text-white no-border" aria-labelledby="dropdownMenuButton1">
										<div class="d-flex flex-column green p-4">
										<input type="hidden" name="controller" value="patientslist">
											<table class="text-white ">
												<tr>
													<td>
														<input type="date" class="form-control" name="dateMin">
													</td>
													<td>
														<input type="date" class="form-control" name="dateMax">
													</td>
												</tr>
												<tr>
													<td>
														<select name="medecin" class="form-select text-green">
															<option value="%">MEDECIN</option>
															<?php
															while ($row = $medecin->fetch()) {
																echo "<option value='" . $row['numRPPS'] . "'>" . $row['nom'] . " " . $row['prenom'] . "</option>";
															}
															?>
														</select>
													</td>
													<td>
														<select name="pValeurASMR" class="form-select text-green">
															<option value="%"></option>
															<?php
															?>
														</select>
													</td>
												</tr>
											</table>
										</div>
									</div>
								
								</div>
							</form>
						</div>				

					</div>
				</nav>

				<span class="fs-1 d-md-none d-sm-block text-green"> Liste Patients </span>
				<!-- content -->
				<div class=" d-flex text-green justify-content-start">
					<?php echo count($patients) ?> resultats
				</div>
				<div class="row h-100 align-items-center text-center">
					<!-- Portail de connexion -->
					<div class="container ">
						<div class="row justify-content-center">

							<div class=" col-md-10 col-xl-12 col-sm-7 col-12 border-2 p-5">
								<div class=" h-50 table-responsive">
								<table class="table table-striped lightGreen">
									<thead class="sticky-top bg-white text-dark  ">
									<tr>
										<th>Numéro de sécurité sociale</th>
										<th>Nom</th>
										<th>Prenom</th>
										<th>Date de naissance</th>
										<th>Medecin Traitant</th>
										<th>Numéro de téléphone</th>
										
										<th>Adresse</th>
										
										
										<th></th>
									</tr>
								</thead>
									<?php 
									foreach ($patients as $row) {
									echo "<tr>"
											 ."<td>" . $row['numSecu'] . "</td>"
											 
											 ."<td>" . $row['nom'] . "</td>"
											 ."<td>" . $row['prenom'] . "</td>"
											 ."<td>" . $row['dateNaissance'] . "</td>"
											 ."<td>" . ($row['medecinRef'] != 0 ? $row['medecinRef'] : "Non définie"). "</td>"
											 ."<td> 0" . $row['numTel'] . "</td>"
											 
											 ."<td>" . $row['adresse'] . "</td>"
											 
											
									?>
									<td>
										
									
									<div class="dropdown  ">
										<span class=" material-symbols-outlined" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
									
											more_horiz

										</span>
										<div class="p-0 border-2 dropdown-menu green dropdown-menu-end text-white no-border" aria-labelledby="dropdownMenuButton1">
											<form action="index.php" action="POST" class="d-flex flex-column green">
												
												<table class="text-white ">
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="controller" value="patientslist">
														<input type="hidden" name="action" value="goFichePatient">
														<input type="hidden" name="idPatient" value="<?php echo $row['idPatient'] ?>">
														<tr><input type="submit" class="btn text-white text-decoration-underline text-end" value="Afficher"> </tr>
													</form>
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="idPatient" value="<?php echo $row['idPatient'] ?>">
														<a  href="#exampleModal" class="btn text-white text-decoration-underline text-end" data-bs-toggle="modal" class="btn green" name="modif" onclick="add('<?php echo  $row['nom'] . " " . $row['prenom'] . "','". $row['idPatient']  ?>')">Supprimer</a>
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
				</div>
				<div class="d-flex flex-row justify-content-end">
					<div class="d-flex me-2 py-2 px-3 border-1 green">
						<form>
							<input type="hidden" name="nextAction" value="addPatient">
							<input type="hidden" name="action" value="goEditPatient">
							<input type="hidden" name="controller" value="patientslist">
							<input type="submit" class="green no-border text-white" value="Ajouter un patient">
						</form>
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
		    				<span>Etes vous sur de vouloir supprimer le patient ?</span>
		    				<span>Toutes ses visites seront perdue .</span>
		    			</div>
		    			<div class = "d-flex justify-content-end p-3 gap-3">
		    				<input type="submit" class="green no-border text-white me-2 py-2 px-3 border-1" data-bs-dismiss="modal" value="Annuler">
		    				<form>		
		    					<input type="submit" class="green no-border text-white me-2 py-2 px-3 border-1" value="confirmer">
								<input type="hidden" name="controller" value="patientslist">
								<input type="hidden" name="action" value="deletePatient">
		    					<input type="hidden" name="idPatient" value="" id ="code">
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
