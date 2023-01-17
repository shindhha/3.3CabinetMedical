<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
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
			<div id="menu" class="pt-3 menu z-index-dropdown col-md-1 col-4 d-md-flex d-none flex-column gap-3 blue h-100 align-items-center">
				<span onclick="manageClass('menu','d-none')" class="material-symbols-outlined d-block d-md-none text-end w-100">arrow_back</span>
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
			<div class="col-md-11 h-75 text-center h-11">
				<!-- Bandeau outils -->	
				<nav class="  row h-11 navbar navbar-expand-lg navbar-light green">
					<div class="d-flex px-md-5 container-fluid green">
						<span class="material-symbols-outlined text-start d-block d-md-none" onclick="manageClass('menu','d-none')">menu</span>
						<span class="h1">Visite</span>
						<form>
								<input type="hidden" name="action" value="deconnexion">
								<input type="submit" class="btn btn-danger" value="Deconnexion">
							</form>
					</div>
				</nav>
                    <!-- Bandeau Patient -->
                    <div class="blue row">
                        <div class="d-flex justify-content-between">
                            <span></span>
                            <div><?php echo $patient['nom'] . " " . $patient['prenom']?></div>
                            <div></div>
                        </div>
                    </div>

                <div class="row d-flex flex-column gap-3 mx-auto col-8">
                    <div class="d-flex flex-row justify-content-between text-dark px-5">
                        <span>Motif : <?php echo $visite['motifVisite'] ; ?></span>
                        <span>Date :  <?php echo $visite['dateVisite']?></span>
                    </div>
                    <div class="d-flex flex-column text-start text-dark d-flex flex-column gap-3">
                        Description
                        <div class="border border-dark px-2 pb-4 ">
                            <?php echo $visite['Description'] ?>
                        </div>
                        Conclusion
                        <div class="border border-dark px-2 pb-4">
                            <?php echo $visite['Conclusion'] ?>
                        </div>
                    </div>
                </div>
                <!-- content -->
                <div class="row align-items-center text-center">
                    <div class="container h-100">
                        <div class="row justify-content-center h-75">
                            <div class=" col-md-10 col-xl-12 col-sm-7 col-12 success border-2">
                                <div class="text-danger text-start"><?php if(isset($addMedicError)) echo $addMedicError; ?></div>
                                <div class=" text-dark text-start h2"> Liste des medicaments prescrit </div>
                                <div class="table-responsive h-75 overflow-scroll">
                                    <table class="table table-striped lightGreen border border-dark">
                                        <thead class="sticky-top bg-white text-dark  ">
                                            <tr>
                                                <th>Designation</th>
                                                <th>Presentation</th>
                                                <th>Instruction	</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <?php
                                            foreach ($drugsVisite as $row) {
                                            echo "<tr>"
                                                 ."<td>" . $row['designation'] . "</td>"
                                                 ."<td>" . $row['libellePresentation'] . "</td>"
                                                 ."<td>" . $row['instruction'] . "</td>";
                                        ?>
                                        <td>
                                        <div class="dropdown">
                                            <span class="material-symbols-outlined" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
                                                more_horiz
                                            </span>
                                            <div class="p-0 text-end dropdown-menu dropdown-menu-end green text-white no-border" aria-labelledby="dropdownMenuButton1">
                                                <table class="text-white ">
                                                    <form action="index.php" method="POST"  class="d-flex flex-column green text-end">
                                                        <input type="hidden" name="controller" value="medicamentslist">
                                                        <input type="hidden" name="action" value="goFicheMedicament">
                                                        <input type="hidden" name="codeCIP7" value="<?php echo $row['codeCIP7'] ?>">
                                                        <tr><input type="submit" class="btn text-white text-decoration-underline text-end" value="Afficher"> </tr>
                                                    </form>
                                                    <form action="index.php" method="POST" class="d-flex flex-column green">
                                                        <input type="hidden" name="controller" value="patientslist">
                                                        <input type="hidden" name="action" value="deleteMedicament">
                                                        <input type="hidden" name="codeCIP7" value="<?php echo $row['codeCIP7'] ?>">
                                                        <tr><input class="btn text-white text-decoration-underline text-end" type="submit" value="Supprimer"></tr>
                                                    </form>

                                                        <a class="btn text-white text-decoration-underline text-end " data-bs-toggle="modal" href="#exampleModal" onclick="add('<?php echo $row['libellePresentation']."','". $row['codeCIP7'] . "','" . $row['instruction'] ?>')"  >Editer</a>

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
                                <div class="d-flex flex-row justify-content-end ">
                                    <div class="d-flex me-2 py-2 px-3 border-1 green">
                                        <form>
                                            <input type="hidden" name="controller" value="patientslist">
                                            <input type="hidden" name="action" value="generatePdf">
                                            <input type="submit" class="green no-border text-white"  value="Télécharger l'ordonnance">
                                        </form>
                                    </div>
                                    <div class="d-flex me-2 py-2 px-3 border-1 green">
                                        <form>
                                            <input type="hidden" name="idVisite" value="<?php echo $visite['idVisite'] ?>">
                                            <input type="hidden" name="controller" value="medicamentslist">
                                            <input type="submit" class="green no-border text-white"  value="Ajouter un medicament">
                                        </form>
                                    </div>
                                    <div class="d-flex me-2 py-2 px-3 border-1 green">
                                        <form>
                                            <input type="hidden" name="nextAction" value="updateVisite">
                                            <input type="hidden" name="action" value="goEditVisite">
                                            <input type="hidden" name="controller" value="patientslist">
                                            <input type="submit" class="green no-border text-white" value="Modifier la visite">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		 	<div class="modal-dialog modal-xl">

		    	<div class="modal-content gap-2">
		    		<div class = "col-12 green d-flex text-start p-3 align-middle">
		    			<span id ="libelle"></span>
		    		</div>
		    		<form>
		    		<div class = "col-12 bg-white h-50 d-flex flex-column text-black text-start px-3">
		    			<span> instruction medicament:</span>
		    			<textarea id="instruction" name="instruction"></textarea>
		    		</div>
		    		<div class = "d-flex justify-content-end p-3">
		    			
		    				<input type="submit" value="confirmer">
		    				<input type="hidden" name="controller" value = "patientslist">
		    				<input type="hidden" name="action" value = "editInstruction">
		    				<input type="hidden" name="codeCIP7" value="" id ="code">
		    			
		    		</div>
		    		</form>
		    	</div>
			</div>
		</div>
		<script type="text/javascript" src="scripts/script.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>
