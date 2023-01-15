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

					<span class="d-flex display-3 align-items-center justify-content-center material-symbols-outlined">
						medication
					</span>
				</a>
				<a href="index.php?controller=patientslist" class=" green border-1 ratio ratio-1x1">
					<span class="d-flex display-3 justify-content-center align-items-center material-symbols-outlined">
						groups
					</span>
				</a>
			</div>
			<!-- Main page -->
			<div class="col-md-11 h-75 text-center">
				<!-- Bandeau outils -->	
				
				<nav class="  row h-15 navbar navbar-expand-lg navbar-light green">
					<div class="d-flex justify-content-between px-5 container-fluid green">
						
						<span class="h1 d-md-block d-none"> Liste Médicaments </span>
						
							<!-- Barre de recherche -->
						<form class="d-flex align-items-center" action="index.php" action="POST">
							<div class="d-flex me-2 py-2 px-3 bg-white border-1">
								<input name="pPresentation" class="no-border" type="search" placeholder="Mots clef" value="<?php echo $pPresentation; ?>" onkeyup="showHint(this.value)" aria-label="Search">
								<input type="submit" class="no-border bg-white material-symbols-outlined text-black" value="search">  

							</div>

							<!-- Filtre -->
							<div class="dropdown green">
								<span class="p-3 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
									Filtres
								</span>
								<div class="p-0  dropdown-menu dropdown-menu-end green text-white no-border" aria-labelledby="dropdownMenuButton1">
									<div class="d-flex flex-column green p-4">
										<input type="hidden" name="controller" value="medicamentslist">
										<table class="text-white ">
											<tr>
												<td>
													<select name="pEtat" class="form-select text-green">
														<option value="-1"<?php if ($pEtat == -1) echo "selected='selected'"; ?>>Etat Commercialisation</option>
														<option value="1" <?php if ($pEtat == 1) echo "selected='selected'"; ?>>Commercialisé</option>
														<option value="0" <?php if ($pEtat == 0) echo "selected='selected'"; ?>>Non Commercialisé</option>
													</select>
													
												</td>
												<td>
													<select name="pSurveillance" class="form-select text-green">
														<option value="-1"<?php if ($pSurveillance == -1) echo "selected='selected'"; ?>>Surveillance Renforcée</option>
														<option value="1" <?php if ($pSurveillance == 1) echo "selected='selected'"; ?>>Oui</option>
														<option value="0" <?php if ($pSurveillance == 0) echo "selected='selected'"; ?>>Non</option>
													</select>
													
												</td>
											</tr>
											<tr>
												<td>
													<select name="pNiveauSmr" class="form-select text-green">
														<option value="%">Valeur SMR</option>
														<?php
														while ($row = $niveauSmr->fetch()) {
															echo "<option";
															if ($pNiveauSmr == $row['libelleNiveauSMR']) echo " selected='selected'";
															echo ">" . $row['libelleNiveauSMR'] . "</option>";
														}
														?>
													</select>
												</td>
												<td>
													<select name="pValeurASMR" class="form-select text-green">
														<option value="%">Valeur ASMR</option>
														<?php
														while ($row = $valeurASMR->fetch()) {
															echo "<option";
															if ($pValeurASMR == $row['valeurASMR']) echo " selected='selected'";
															echo ">" . $row['valeurASMR'] . "</option>";
														}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<td>
													<select name="pformePharma" class="form-select text-green">
														<option value="%">Forme Pharmacie</option>
														<?php
														while ($row = $formePharmas->fetch()) {
															echo "<option";
															if ($pformePharma == $row['formePharma']) echo " selected='selected'";
															echo ">" . $row['formePharma'] . "</option>";
														}
														?>
													</select>
												</td>
												<td>
													<select name="pVoieAdmi" class="form-select text-green">
														<option value="%">Voie d'administration</option>
														<?php
														while ($row = $voieAd->fetch()) {
															echo "<option";
															if ($pVoieAdmi == $row['labelVoieAdministration']) echo " selected='selected'";
															echo ">" . $row['labelVoieAdministration'] . "</option>";
														}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<td>
													<select name="pTauxRem" class=" form-select text-green ">
														<option value="">Taux Remboursement</option>
														<?php

														while ($row = $tauxRemboursements->fetch()) {

															echo "<option";
															if ($pTauxRem == $row['tauxRemboursement']) echo " selected='selected'";
															echo ">" . $row['tauxRemboursement'] . "</option>";
														}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<td>
													<label for="pPrixMin">Prix min :</label>
													<input type="number" name="pPrixMin" value="<?php echo $pPrixMin; ?>">
												</td>
												<td>
													<label for="pPrixMax">Prix Max :</label>
													<input type="number" name="pPrixMax" value="<?php echo $pPrixMax; ?>">
												</td>
											</tr>
										</table>
									</div>
										
								</div>
							</form>


						</div>				

					</div>
				</nav>

				<span class="fs-1 d-md-none d-sm-block text-green"> Liste Medicaments </span>
				<!-- content -->
				<div class=" d-flex text-green justify-content-start">
					<?php echo count($drugs) ?> resultats
				</div>
				<div class="row h-100 align-items-center text-center">
					<!-- Portail de connexion -->
					<div class="container ">
						<div class="row justify-content-center">

							<div class="overflow-scroll h-50 col-md-10 col-xl-12 col-sm-7 col-12 border-2 p-5">
								<table class="table table-striped lightGreen">
									<tr>
										<th>codeCIS</th>
										<th>Valeur SMR</th>
										<th>Valeur ASMR</th>
										<th>Forme Pharmaceutique</th>
										<th>Voie d'administration</th>
										<th>Taux Remboursement</th>
										<th>Prix</th>
										<th>Presentation </th>
										<th>Etat Commercialisation </th>
										<th>Surveillance Renforcé</th>
										<th></th>
									</tr>

									<?php
									$surveillance = "";
									$commercialiser = "";

										foreach ($drugs as $row)  {
											if ($row['etatCommercialisation']) {
												$commercialiser = "Commercialiser";
											} else {
												$commercialiser = "Non Commercialiser";
											}

											if ($row['surveillanceRenforcee']) {
												$surveillance = "OUI";
											} else {
												$surveillance = "NON";
											}

										echo "<tr>"
											 ."<td>" . $row['codeCIS'] . "</td>"
											 ."<td>" . $row['libelleNiveauSMR'] . "</td>"
											 ."<td>" . $row['valeurASMR'] . "</td>"
											 ."<td>" . $row['formePharma'] . "</td>"
											 ."<td>" . $row['labelVoieAdministration'] . "</td>"
											 ."<td>" . $row['tauxRemboursement'] . "</td>"
											 ."<td>" . $row['prix'] . "</td>"
											 ."<td>" . $row['libellePresentation'] . "</td>"
											 ."<td>" . $commercialiser . "</td>"
											 ."<td>" . $surveillance . "</td>"
									?>
									<td>
										
									
									<div class="dropdown ">
										<span class=" material-symbols-outlined" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
									
											more_horiz

										</span>
										<div class="p-0  dropdown-menu dropdown-menu-end text-white no-border" aria-labelledby="dropdownMenuButton1">
											<form action="index.php" action="POST" class="d-flex flex-column green">
												
												<table class="text-white ">
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="controller" value="medicamentslist">
														<input type="hidden" name="action" value="goFicheMedicament">
														<input type="hidden" name="codeCIS" value="<?php echo $row['codeCIS'] ?>">
														<tr><input type="submit" value="Afficher"> </tr>
													</form>
													<form action="index.php" action="POST" class="d-flex flex-column green">
														<input type="hidden" name="controller" value="patientslist">
														<input type="hidden" name="action" value="visite">
														<input type="hidden" name="codeCIS" value="<?php echo $row['codeCIS'] ?>">
														<?php if (isset($ajouter)) {
														?>

															<tr><a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModal" onclick="add('<?php echo $row['libellePresentation']."','". $row['codeCIS']  ?>')" role="button">Ajouter</a></tr>
														<?php
														} ?>
														
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

		</div>
		
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		 	<div class="modal-dialog modal-xl modal-dialog-centered">

		    	<div class="modal-content gap-2">
		    		<div class = "col-12 green d-flex text-start p-3 align-middle">
		    			<span id ="libelle"></span>
		    		</div>
		    		<form>
		    		<div class = "col-12 bg-white h-50 d-flex flex-column text-black text-start px-3">
		    			<span> instruction medicament:</span>
		    			<textarea name="instruction"></textarea>
		    		</div>
		    		<div class = "d-flex justify-content-end p-3">
		    			
		    				<input type="submit" value="confirmer">
		    				<input type="hidden" name="controller" value = "patientslist">
		    				<input type="hidden" name="action" value = "addMedicament">
		    				<input type="hidden" name="codeCIS" value="" id ="code">
		    			
		    		</div>
		    		</form>
		    	</div>
			</div>
		</div>


		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="../scripts/script.js"></script>
	</div>
</body>
</html>
