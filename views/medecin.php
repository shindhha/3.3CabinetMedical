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
                <a href="index.php?controller=administrateur" class="d-md-none">
                    <div class="text-white green border-1 ratio ratio-1x1">
                        <span class="d-flex display-3 text-white align-items-center justify-content-center material-symbols-outlined">
                            article
                        </span>
                    </div>
                </a>
                <a href="index.php?controller=administrateur&action=goListMedecins" class="d-md-none">
                    <div  class=" text-white green border-1 ratio ratio-1x1">
                        <span class="d-flex display-3 text-white justify-content-center align-items-center material-symbols-outlined">
                            groups
                        </span>
                    </div>
                </a>
                <a href="index.php?controller=administrateur" class="text-white d-none d-md-block green border-1 ratio ratio-1x1">

                    <span class="d-flex display-3 align-items-center justify-content-center material-symbols-outlined">
                        article
                    </span>
                </a>
                <a href="index.php?controller=administrateur&action=goListMedecins" class="text-white d-none d-md-block green border-1 ratio ratio-1x1">
                    <span class="d-flex display-3 justify-content-center align-items-center material-symbols-outlined">
                        groups
                    </span>
                </a>
                <a href="index.php?controller=administrateur&action=goErreursImport" class="text-white d-none d-md-block green border-1 ratio ratio-1x1">
                    <span class="d-flex display-3 align-items-center justify-content-center material-symbols-outlined">
                        settings
                    </span>
                </a>
                <a href="index.php?controller=administrateur&action=goErreursImport" class="d-md-none">
                    <div  class=" text-white green border-1 ratio ratio-1x1">
                        <span class="d-flex display-3 text-white justify-content-center align-items-center material-symbols-outlined">
                            settings
                        </span>
                    </div>
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


                    <div class="container-fluid text-green mt-5">
                        <div class="row d-flex flex-column gap-5 gap-md-0 flex-md-row ">
                            <div class="col-12 col-md-6 d-flex flex-column ">
                                <h1>Informations personnelles</h1>
                                <span> AdresseFAddm
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
                            <div class="col-12 col-md-6 d-flex flex-column ">
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
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center text-md-end px-4 pt-3">
                        <form>
                            <input type="hidden" name="controller" value="administrateur">
                            <input type="hidden" name="action" value="goEditMedecin">
                            <input type="hidden" name="nextAction" value="updateMedecin">
                            <input type="submit" class="green no-border text-white me-2 py-2 px-3 border-1" value="Modifier le medecin">
                        </form>
                        
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
