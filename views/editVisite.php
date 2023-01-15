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

						</div>				

					</div>
				</nav>
				<!-- Bandeau Patient -->
				<form>
				<div class="blue row">
					<div class="d-flex justify-content-between">
						<span></span>
						
					</div>
				</div>

				<div class="row text-dark">
					
						
						<div class="d-flex flex-column justify-content-start ">
							<div class="d-flex flex-row justify-content-between">
								<div class="d-flex flex-row"> 
								<span>Motif</span> <input class="form-control" type="text" name="Motif" value="<?php if (isset($visite)) echo $visite[0]['motifVisite']; ?>"> 
								</div>
								<div class="d-flex flex-row">
									<span>Date</span><input class="form-control" type="date" name="Date" max="<?php echo date('Y-m-d'); ?>" value="<?php if (isset($visite)) echo $visite[0]['dateVisite']; ?>">
								</div>
							</div>

							
							<div class="d-flex flex-column text-start">
								<span>Description</span>

								<textarea name="Description">
									<?php if (isset($visite)) echo $visite[0]['Description']; ?>
								</textarea>

							</div>
							
							<div class="d-flex flex-column text-start	">


								<span>Conclusion</span>
								<textarea name="Conclusion">
									<?php if (isset($visite)) echo $visite[0]['Conclusion']; ?>
								</textarea>
							</div>
							
							
							
						</div>
					
				</div>

				<span class="fs-1 d-md-none d-sm-block text-green"> Liste Patients </span>
				<!-- content -->
				<div class="row h-100 align-items-center text-center">
					<!-- Portail de connexion -->
					
					<div class="container ">
						<div class="row justify-content-center">

							<div class="d-flex flex-row justify-content-between">
								<div class="d-flex me-2 py-2 px-3 border-1 green">
								<input type="submit" class="green no-border text-white" value="Annuler">
						
								</div>
								<div class="d-flex me-2 py-2 px-3 border-1 green">
									<input type="hidden" name="action" value="<?php echo $action ?>">
									<input type="hidden" name="controller" value="patientslist">
									<input type="submit" class="green no-border text-white" value="Valider">
						
								</div>
					
							</div>

						</div>

					</div>

				</div>
				
			</form>
			</div>

		</div>


		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>
