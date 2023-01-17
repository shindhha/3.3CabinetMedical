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
			<div id="menu" class="pt-3 menu z-index-dropdown col-md-1 col-4 d-md-flex d-none flex-column gap-3 blue h-100 align-items-center">
				<span onclick="manageClass('menu','d-none')"class="material-symbols-outlined d-block d-md-none text-end w-100">arrow_back</span>
				<div class=" green border-1 ratio ratio-1x1">

				</div>
				<a href="index.php?controller=medicamentslist" class="d-md-none">
					<div class="text-white green border-1 ratio ratio-1x1">
						<span class="d-flex display-3 align-items-center justify-content-center material-symbols-outlined">
							medication
						</span>
					</div>
				</a>
				<a href="index.php?controller=patientslist" class="d-md-none">
					<div  class=" text-white green border-1 ratio ratio-1x1">
						<span class="d-flex display-3 justify-content-center align-items-center material-symbols-outlined">
							groups
						</span>
					</div>
				</a>
				<a href="index.php?controller=medicamentslist" class="text-white d-none d-md-block green border-1 ratio ratio-1x1">

                    <span class="d-flex display-3 align-items-center justify-content-center material-symbols-outlined">
                        medication
                    </span>
                </a>
                <a href="index.php?controller=patientslist" class=" text-white d-none d-md-block green border-1 ratio ratio-1x1">
                    <span class="d-flex display-3 justify-content-center align-items-center material-symbols-outlined">
                        groups
                    </span>
                </a>
			</div>
			<!-- Main page -->
			<div class="col-md-11 h-75 text-center">
				<!-- Bandeau outils -->	
				
				<nav class="  row h-11 navbar navbar-expand-lg navbar-light green">
					<div class="d-flex px-md-5 container-fluid green">
						<span class="material-symbols-outlined text-start d-block d-md-none" onclick="manageClass('menu','d-none')">menu</span>
						<span class="h1"> Fiche Patient </span>
						<form>
								<input type="hidden" name="action" value="deconnexion">
								<input type="submit" class="btn btn-danger" value="Deconnexion">
							</form>
					</div>
				</nav>
				<!-- Bandeau Patient -->
				<form>

					<div class="d-flex justify-content-center ">
						<div class="d-flex flex-column col-12 col-md-8">
							<div class="d-flex flex-row  justify-content-around text-green">
								<div class="d-flex flex-column text-start">
										<span class="" >Nom</span>
										<div class="d-flex ">
											<input class="form-control " type="text" name="nom" value="<?php if (isset($patient['nom'])) echo $patient['nom']; ?>"> 
										</div>
										<span class="" >Prenom</span>
										<div><input class="form-control" type="text" name="prenom" value="<?php if (isset($patient['prenom'])) echo $patient['prenom']; ?>"> </div>
										<span class="" >Adresse</span>
										<div><input class="form-control" type="text" name="adresse" value="<?php if (isset($patient['adresse'])) echo $patient['adresse']; ?>"> 
										</div>
										<span class="" >Code Postal</span>
										<div>
											<?php if (isset($codePostalError)) echo $codePostalError; ?>
											<input class="form-control" type="number" name="codePostal" min="1001" max="98800" value="<?php if (isset($patient['codePostal'])) echo $patient['codePostal']; ?>">
										</div>
                                        <span class="" >Ville</span>
                                        <div>
                                            <?php if (isset($villeError)) echo $villeError; ?>
                                            <input class="form-control" type="text" name="ville" min="1001" max="98800" value="<?php if (isset($patient['ville'])) echo $patient['ville']; ?>">
                                        </div>
										<span class="" >n°Telephone</span>
										<div><input class="form-control" type="number" name="numTel" max="999999999" value="<?php if (isset($patient['numTel'])) echo $patient['numTel']; ?>">
										</div>

								</div>
								<div class="d-flex flex-column text-start">
									<span >Medecin Traitant</span>
										<select name="medecinRef" class="form-select">
											<?php if (isset($medecinError)) echo $medecinError; ?>
											<option value="0">Medecin Traitant</option>
											<?php 
											while ($row = $medecins->fetch()) {
												echo "<option value='". $row['numRPPS']."'";
												if (isset($patient['medecinRef']) && $patient['medecinRef'] == $row['numRPPS']) {
													echo "selected='selected'";
												}
												echo ">" . $row['nom'] . " " . $row['prenom'] . "</option>";
											}
											?>
										</select>
										<span >Sexe</span>
										<select name="sexe" class="form-select">
											<option value="0">Femme</option>
											<option value="1">Homme</option>
										</select>
										<span >Numéro sécurité sociale</span>
										<div>
											<?php if (isset($numSecuError)) echo $numSecuError; ?>
											<input class="form-control" type="text" name="numSecu" value="<?php if (isset($patient['numSecu'])) echo $patient['numSecu']; ?>">
										</div>
										<span >Date Naissance</span>
										<div><input class="form-control" type="date" max="<?php echo date('Y-m-d'); ?>" name="dateNaissance" value="<?php if (isset($patient['dateNaissance'])) echo $patient['dateNaissance']; ?>">
										</div>
										<span >Lieu Naissance</span>
										<div><input class="form-control" type="text" name="LieuNaissance" value="<?php if (isset($patient['LieuNaissance'])) echo $patient['LieuNaissance']; ?>">
										</div>
                                        <span>Email</span>
                                        <div>
                                            <?php if (isset($emailError)) echo $emailError; ?>
                                            <input class="form-control" type="text" name="email" value="<?php if (isset($patient['email'])) echo $patient['email']; ?>">
                                        </div>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex justify-content-center">
						<div class="d-flex col-12 col-md-10 text-start mb-2 flex-column text-green">
							<h1>Notes</h1> 
							<textarea  name="notes" rows="12" cols="33"><?php if (isset($patient['notes'])) echo $patient['notes'];?></textarea>
						</div>
					</div>
						<!-- Portail de connexion -->
					<div class="d-flex justify-content-end justify-content-md-center">
						<div class="d-flex col-md-10 flex-row justify-content-between">	
							<div class="d-flex me-2 py-2 px-3 border-1 bg-danger">
								<input type="hidden" name="controller" value="patientslist">
								<input type="submit" class="bg-danger no-border text-white" value="Annuler" onclick="goTo('index','patientslist')">	
							</div>
							<div class="d-flex me-2 py-2 px-3 border-1 green">
								<input type="hidden" id="controller" name="controller" value="patientslist">
								<input type="hidden" id="action" name="action" value="<?php echo $action ?>">
								<input type="submit" class="green no-border text-white" value="Valider">		
							</div>
						</div>
					</div>
				</form>
			</div>

		</div>

		<script type="text/javascript" src="scripts/script.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>
