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
        <div class="col-md-11 h-100 text-center">
            <!-- Bandeau outils -->
            <nav class="row h-11 navbar navbar-expand-lg navbar-light green">
                <div class="d-flex px-md-5 container-fluid green">
                    <span class="material-symbols-outlined text-start d-block d-md-none" onclick="manageClass('menu','d-none')">menu</span>
                    <span class="h1"> Fiche Medecin </span>
                    <form>
                        <input type="hidden" name="action" value="deconnexion">
                        <input type="submit" class="btn btn-danger" value="Deconnexion">
                    </form>
                </div>
            </nav>
            
                
                    <!-- Bandeau Patient -->
                    <form method="get" action="index.php" class="h-75 overflow-scroll w-100">
                        <input type="hidden" id="action" name="action" value="<?php echo $nextAction ?>">
                        <input type="hidden" id="controller" name="controller" value="administrateur">
                        <div class="container-fluid text-green text-center text-md-start ">
                            <div class="row ">
                                <div class="col-12 col-md-6">
                                    <h1>Informations personnelles</h1>
                                    <div>
                                        Nom
                                        <input type="text" name="nom" class="form-control" value="<?php if(isset($medecin['nom'])) echo $medecin['nom']; ?>" class="input-grow" placeholder="Nom">
                                    </div>
                                    <div>
                                        Adresse
                                        <input type="text" name="adresse" class="form-control" value="<?php if(isset($medecin['adresse'])) echo $medecin['adresse'];?>" class="input-grow">
                                    </div>
                                    <div>
                                        Code postal / Ville
                                        <div class="d-flex flex-row">
                                        <input type="number" class="form-control"  name="codePostal" min="1001" max="98800" value="<?php if(isset($medecin['codePostal'])) echo $medecin['codePostal']; ?>" class="input-grow"> / <input type="text" name="ville" class="form-control" value="<?php if(isset($medecin['codePostal'])) echo $medecin['ville']; ?>" class="input-grow">
                                        </div>
                                    </div>
                                    <div>
                                        Téléphone
                                        <input type="number" name="numTel" class="form-control" min="100000000" max="799999999" value="<?php if(isset($medecin['numTel'])) echo $medecin['numTel'];?>" class="input-grow">
                                    </div>
                                    <div>
                                        <?php if (isset($emailError)) echo $emailError; ?>
                                        Email
                                            <input type="text" name="email" class="form-control" value="<?php if(isset($medecin['email'])) echo $medecin['email']; ?>" class="input-grow">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h1>Informations professionnelles</h1>
                                    <div>
                                        Prenom
                                        <input type="text" name="prenom" class="form-control" value="<?php if(isset($medecin['nom'])) echo $medecin['prenom'];?>" class="input-grow" placeholder="Prénom">
                                    </div>
                                    <div>
                                        Numéro RPPS
                                        <?php if (isset($numRPPSError)) echo $numRPPSError; ?>
                                        <input type="text" name="numRPPS" class="form-control" value="<?php if(isset($medecin['numRPPS'])) echo $medecin['numRPPS']; ?>" class="input-grow">
                                    </div>
                                    <div>
                                        Secteur d'activité
                                        <input type="text" name="activite" class="form-control" value="<?php if(isset($medecin['activite'])) echo $medecin['activite'];?>" class="input-grow">
                                    </div>
                                    <div>
                                        Mot de passe
                                        <input type="text" name="password" class="form-control" value="<?php if(isset($medecin['password'])) echo $medecin['password'];?>" class="input-grow">
                                    </div>
                                    <div>
                                        <?php if(isset($dateError)) echo $dateError; ?>
                                        Date du début d'activité
                                        <input type="date" name="dateDebutActivite" class="form-control" max="<?php echo date('Y-m-d'); ?>" value="<?php if(isset($medecin['dateDebutActivites'])) echo $medecin['dateDebutActivites']; ?>" class="input-grow">
                                    </div>
                                    
                                </div>
                                
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-flex col-12 flex-row justify-content-around">
                                    <input type="submit" class="btn btn-danger btn-lg" value="Annuler" onclick="goTo('goListMedecins','administrateur');"> 
                                    <input class="btn btn-success btn-lg" type="submit" value="Valider">
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
