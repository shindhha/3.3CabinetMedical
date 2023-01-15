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
            <a href="index.php?controller=administrateur" class="green border-1 ratio ratio-1x1">
					<span class="d-flex display-1 align-items-center justify-content-center material-symbols-outlined">
						settings
					</span>
            </a>
            <a href="index.php?controller=administrateur&action=goListMedecins" class="green border-1 ratio ratio-1x1">
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

                    <span class="h1 d-md-block d-none"> Fiche médecin </span>

                </div>
            </nav>
            <!-- Bandeau Patient -->
            <form method="post" action="index.php">

                <div class="blue row">
                    <div class="d-flex justify-content-between">
                        <span></span>
                        <div>
                            <span><?php if(isset($medecin['nom'])) echo $medecin['nom']; ?></span> 
                            <span><?php if(isset($medecin['prenom'])) echo $medecin['prenom']; ?></span>
                        </div>
                        <div></div>
                    </div>
                </div>

                <div class="row">
                    <div class="d-flex flex-row justify-content-between text-green">

                        <div class="d-flex flex-column p-md-5">
                            <h1>Informations personnelles</h1>
                            <span> Adresse
                                <div class="border border-1 border-green d-flex">
                                    <span> <?php if(isset($medecin['adresse'])) echo $medecin['adresse']; ?>
                                    </span>
                                </div>
                            </span>
                            <span> Code postal / Ville
                                <div class="border border-1 border-green d-flex">
                                    <span><?php if(isset($medecin['codePostal'])) echo $medecin['codePostal']; ?></span> / <span> <?php if(isset($medecin['ville'])) echo $medecin['ville']; ?></span>
                                </div>
                            </span>
                            <span> Téléphone
                                <div class="border border-1 border-green d-flex">
                                    <span> <?php if(isset($medecin['numTel'])) echo $medecin['numTel']; ?></span>
                                </div>
                            </span>
                            <span>Email
                                <div class="border border-1 border-green d-flex">
                                    <span> <?php if(isset($medecin['email'])) echo $medecin['email']; ?></span>
                                </div>
                            </span>

                        </div>

                        <div class="d-flex flex-column p-md-5">
                            <h1>Informations professionnelles</h1>
                            <span> Numéro RPPS
                                <div class="border border-1 border-green d-flex">
                                    <span> <?php if(isset($medecin['numRPPS'])) echo $medecin['numRPPS']; ?></span>
                                </div>
                            </span>
                            <span> Secteur d'activité
                                <div class="border border-1 border-green d-flex">
                                    <span> <?php if(isset($medecin['activite'])) echo $medecin['activite']; ?></span>
                                </div>
                            </span>
                            <span> Date d'enregistrement
                                <div class="border border-1 border-green text-black">
                                    <span><?php if(isset($medecin['dateInscription'])) echo $medecin['dateInscription']; ?></span>
                                </div>
                            </span>
                            <span>
                                Date du début d'activité
                                <div class="border border-1 border-green text-black">
                                    
                                    <span><?php if(isset($medecin['dateDebutActivites'])) echo $medecin['dateDebutActivites']; ?></span>
                                </div>
                            </span>
                            <span> 
                                <div class="border border-1 border-green d-flex text-green">
                                    
                                </div>
                            </span>
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
