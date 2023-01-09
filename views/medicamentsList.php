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
						
						<span class="h1 d-md-block d-none"> Liste Médicaments </span>
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
													<select class="form-select text-green">
														<option>Valeur SMR</option>
													</select>
												</td>
												<td>
													<select class="form-select text-green">
														<option>Valeur ASMR</option>
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

										<input type="submit" value="Rechercher" >
									</div>
								</form>
							</div>

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

							<div class="overflow-scroll h-50 col-md-10 col-xl-9 col-sm-7 col-12 green border-2 p-5">
								<table class="">
									<tr>
										<th>codeCIS</th>
										<th>Valeur SMR</th>
										<th>Valeur ASMR</th>
										<th>Forme Pharmaceutique</th>
										<th>Voie d'administration</th>
										<th>Taux Remboursement</th>
										<th>Prix</th>
										<th>Désignation </th>
										<th>Etat Commercialisation </th>
										<th>Surveillance Renforcé</th>
										<th>Designation Principe Actif </th>
									</tr>

									<?php
									var_dump($d);
									$surveillance = "";
									$commercialiser = "";

										foreach ($drugs as $row) {
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
											 ."<td>" . "</td>"
											 ."<td>" . "</td>"
											 ."<td>" . $row['formePharma'] . "</td>"
											 ."<td>" . $row['labelVoieAdministration'] . "</td>"
											 ."<td>" . $row['tauxRemboursement'] . "</td>"
											 ."<td>" . $row['prix'] . "</td>"
											 ."<td>" . $row['designation'] . "</td>"
											 ."<td>" . $commercialiser . "</td>"
											 ."<td>" . $surveillance . "</td>"
											 ."<td>" .  "</td>"
											 ."</tr>";
										} 
									?>
								</table>

							</div>


						</div>

					</div>
				</div>
			</div>

		</div>


		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>
