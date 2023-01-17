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
			<div class="col-md-11 h-100 text-center">
				<!-- Bandeau outils -->	
				
				<nav class="  row h-11 navbar navbar-expand-lg navbar-light green">
					<div class="d-flex justify-content-between px-5 container-fluid green">
                        <span class="material-symbols-outlined  d-block d-md-none col-1" onclick="manageClass('menu','d-none')">menu</span>
                        
						<span class="h1 d-md-block d-none"> Fiche Medicament </span>
                        <form class="ms-2">
                                <input type="hidden" name="action" value="deconnexion">
                                <input type="submit" class="btn btn-danger" value="Deconnexion">
                        </form>
					</div>

				</nav>
                <div class="row blue">
                    <?php echo $medicament['designation']?>
                </div>
				<span class="fs-1 d-md-none d-sm-block text-green"> Fiche Medicament </span>
				<!-- content -->
				<div class="row h-89 align-items-center text-center">
					<!-- Portail de connexion -->
					<div class="container overflow-scroll h-100">
						<div class="row justify-content-center text-dark">
                            <div class="col-xl-10 col-md-10 col-sm-12 col-12 text-start">
                                <div id="accordion">
                                    <div class="card">
                                        <div class="card-header" id="card-info-gener">
                                            <h5 class="mb-0">
                                                <a data-bs-toggle="collapse" href="#card-info-gener-collapse" role="button">
                                                    Informations générales
                                                </a>
                                            </h5>
                                        </div>

                                        <div id="card-info-gener-collapse" class="collapse" aria-labelledby="card-info-gener" data-parent="#accordion">
                                            <div class="card-body">
                                                <p>
                                                    <span class="text-decoration-underline">Designation :</span> <?php echo $medicament['designation'] ?><br>
                                                    <span class="text-decoration-underline">Présentation :</span> <?php echo $medicament['libellePresentation'] ?><br>
                                                    <span class="text-decoration-underline">Forme pharmaceutique :</span> <?php echo $medicament['formePharma'] ?><br>
                                                    <span class="text-decoration-underline">Voir d'administration :</span> <?php echo $medicament['labelVoieAdministration'] ?><br>
                                                    <span class="text-decoration-underline">Code CIP :</span> <?php echo $medicament['codeCIP7'] ?> / <?php echo $medicament['codeCIP13'] ?><br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="card-info-subs">
                                            <h5 class="mb-0">
                                                <a data-bs-toggle="collapse" href="#card-info-subs-collapse" role="button">
                                                    Informations sur les substances
                                                </a>
                                            </h5>
                                        </div>

                                        <div id="card-info-subs-collapse" class="collapse" aria-labelledby="card-info-subs" data-parent="#accordion">
                                            <div class="card-body">
                                                <p>
                                                    <span class="text-decoration-underline">Groupe générique :</span> <?php echo $medicament['labelGroupeGener'] ?><br>
                                                    <span class="text-decoration-underline">Substance active :</span> <?php echo $medicament['codesubstance'] ?><br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="card-info-remboursement">
                                            <h5 class="mb-0">
                                                <a data-bs-toggle="collapse" href="#card-info-remboursement-collapse" role="button">
                                                    Remboursement
                                                </a>
                                            </h5>
                                        </div>

                                        <div id="card-info-remboursement-collapse" class="collapse" aria-labelledby="card-info-subs" data-parent="#accordion">
                                            <div class="card-body">
                                                <p>
                                                    <span class="text-decoration-underline">Prix :</span> <?php echo $medicament['prix'] ?> €<br>
                                                    <span class="text-decoration-underline">Remboursement :</span> <?php echo $medicament['tauxRemboursement'] ?> %<br>
                                                    <span class="text-decoration-underline">Indication de remboursement : </span> <?php echo $medicament['IndicationRemboursement'] ?><br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="card-smr">
                                            <h5 class="mb-0">
                                                <a data-bs-toggle="collapse" href="#card-smr-collapse" role="button">
                                                    Service médical rendu (SMR)
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="card-smr-collapse" class="collapse" aria-labelledby="card-smr" data-parent="#accordion">
                                            <div class="card-body">
                                                    <?php
                                                    if (count($lteSmr) == 0) {
                                                        echo "Aucune donnée disponible.";
                                                    } else {
                                                        echo "<table class='table table-success table-bordered table-striped table-hover'>";
                                                            echo "<tr class='fw-bold'>";
                                                                echo "<th class='text-center'>Valeur</th>";
                                                                echo "<th class='text-center'>Date</th>";
                                                                echo "<th>Motif d'évaluation</th>";
                                                                echo "<th>Résumé</th>";
                                                            echo "</tr>";

                                                            foreach ($lteSmr as $line) {
                                                                echo "<tr class='fw-light'>";
                                                                    echo "<td class='text-center'>" . $line['libelleNiveauSMR'] . "</td>";

                                                                    if ($line['dateAvis'] != null) {
                                                                        $dateFormat = date_format(date_create($line['dateAvis']), 'd/m/Y');
                                                                    } else {
                                                                        $dateFormat = "Date inconnue.";
                                                                    }
                                                                    if ($line['lienPage'] != '') {
                                                                        echo "<td class='text-center'><a href='" . $line['lienPage'] . "' target='_blank'>" . $dateFormat . "</a></td>";
                                                                    } else {
                                                                        echo "<td class='text-center'>" . $dateFormat . "</td>";
                                                                    }

                                                                    echo "<td>" . $line['libelleMotifEval'] . "</td>";
                                                                    echo "<td>" . $line['libelleSmr'] . "</td>";
                                                                echo "</tr>";
                                                            }
                                                        echo "</table>";
                                                    }
                                                    ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="card-asmr">
                                            <h5 class="mb-0">
                                                <a class="" data-bs-toggle="collapse" href="#card-asmr-collapse" role="button">
                                                    Amélioration du service médical rendu (ASMR)
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="card-asmr-collapse" class="collapse" aria-labelledby="card-asmr" data-parent="#accordion">
                                            <div class="card-body">
                                                <?php
                                                if (count($lteASMR) == 0) {
                                                    echo "Aucune donnée disponible.";
                                                } else {
                                                    echo "<table class='table table-success table-bordered table-striped table-hover'>";
                                                        echo "<tr class='fw-bold'>";
                                                            echo "<th class='text-center'>Valeur</th>";
                                                            echo "<th class='text-center'>Date</th>";
                                                            echo "<th>Motif d'évaluation</th>";
                                                            echo "<th>Résumé</th>";
                                                        echo "</tr>";

                                                        foreach ($lteASMR as $line) {
                                                            echo "<tr class='fw-light'>";
                                                            echo "<td class='text-center'>" . $line['valeurASMR'] . "</td>";

                                                            if ($line['dateAvis'] != null) {
                                                                $dateFormat = date_format(date_create($line['dateAvis']), 'd/m/Y');
                                                            } else {
                                                                $dateFormat = "Date inconnue.";
                                                            }
                                                            if ($line['lienPage'] != '') {
                                                                echo "<td class='text-center'><a href='" . $line['lienPage'] . "' target='_blank'>" . $dateFormat . "</a></td>";
                                                            } else {
                                                                echo "<td class='text-center'>" . $dateFormat . "</td>";
                                                            }

                                                            echo "<td>" . $line['libelleMotifEval'] . "</td>";
                                                            echo "<td>" . $line['libelleAsmr'] . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    echo "</table>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="card-autres">
                                            <h5 class="mb-0">
                                                <a class="" data-bs-toggle="collapse" href="#card-autres-collapse" role="button">
                                                    Autres
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="card-autres-collapse" class="collapse" aria-labelledby="card-autres" data-parent="#accordion">
                                            <div class="card-body">
                                                <p>
                                                    <span class="text-decoration-underline">Code CIS :</span> <?php echo $medicament['codeCIS']?><br>
                                                    <?php
                                                    if ($medicament['agrementCollectivites'] == 1) {
                                                        echo "Cette présentation est <span class='text-decoration-underline'>agréée aux collectivités</span>";
                                                    } else {
                                                        echo "Cette présentation n'est pas <span class='text-decoration-underline'>agréée aux collectivités</span>";
                                                    }
                                                    echo "<br>";
                                                    if ($medicament['dateAMM'] != null) {
                                                        $dateFormat = "(" . date_format(date_create($medicament['dateAMM']), 'd/m/Y') . ")";
                                                    } else {
                                                        $dateFormat = "";
                                                    }
                                                    echo "<span class='text-decoration-underline'>Statut de l'autorisation :</span> " . strtolower($medicament['statutAdAMM']) . " " . $dateFormat . "<br>";
                                                    ?>
                                                    <span class='text-decoration-underline'>Condition de prescription et de délivrance :</span> <?php echo $medicament['labelcondition']?><br>

                                                    <?php
                                                    if ($medicament['dateCommrcialisation'] != null) {
                                                        $dateFormat = date_format(date_create($medicament['dateCommrcialisation']), 'd/m/Y');
                                                    } else {
                                                        $dateFormat = "Date inconnue.";
                                                    }
                                                    ?>
                                                    <span class="text-decoration-underline">Date commercialisation :</span> <?php echo $dateFormat ?> par <?php echo $medicament['labelTitulaire']?> <br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="scripts/script.js"></script>
	</div>
</body>
</html>
