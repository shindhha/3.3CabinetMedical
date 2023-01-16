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
                        <div><input type="text" name="nom" value="<?php if(isset($medecin['nom'])) echo $medecin['nom']; ?>" class="input-grow" placeholder="Nom"> <input type="text" name="prenom" value="<?php if(isset($medecin['nom'])) echo $medecin['prenom'];?>" class="input-grow" placeholder="Prénom"></div>
                        <div></div>
                    </div>
                </div>

                <div class="row">

                    <div class="d-flex flex-row justify-content-between text-green">

                        <div class="d-flex flex-column p-md-5">
                            <h1>Informations personnelles</h1>
                            <span> Adresse
                                <div class="border border-1 border-green enable-flex">
                                    <input type="text" name="adresse" value="<?php if(isset($medecin['adresse'])) echo $medecin['adresse'];?>" class="input-grow">
                                </div>
                            </span>
                            <span> Code postal / Ville
                                <div class="border border-1 border-green enable-flex">
                                    <input type="number" name="codePostal" min="1001" max="98800" value="<?php if(isset($medecin['codePostal'])) echo $medecin['codePostal']; ?>" class="input-grow"> / <input type="text" name="ville" value="<?php if(isset($medecin['codePostal'])) echo $medecin['ville']; ?>" class="input-grow">
                                </div>
                            </span>
                            <span> Téléphone
                                <div class="border border-1 border-green enable-flex">
                                    <input type="number" name="numTel" min="600000000" max="799999999" value="<?php if(isset($medecin['numTel'])) echo $medecin['numTel'];?>" class="input-grow">
                                </div>
                            </span>
                            <span>
                                <?php if (isset($emailError)) echo $emailError; ?>
                                Email

                                <div class="border border-1 border-green enable-flex">
                                    <input type="text" name="email" value="<?php if(isset($medecin['email'])) echo $medecin['email']; ?>" class="input-grow">
                                </div>
                            </span>
                            <form method="post" action="index.php">
                                <input type="hidden" name="controller" value="administrateur">
                                <input type="hidden" name="action" value="listMedecins">
                                <button type="submit">Annuler</button>
                            </form>
                        </div>

                        <div class="d-flex flex-column p-md-5">
                            <h1>Informations professionnelles</h1>
                            <span> 
                                <?php if (isset($numRPPSError)) echo $numRPPSError; ?>
                                Numéro RPPS
                                <div class="border border-1 border-green enable-flex">

                                    <input type="text" name="numRPPS" value="<?php if(isset($medecin['numRPPS'])) echo $medecin['numRPPS']; ?>" class="input-grow">
                                </div>
                            </span>
                            <span> Secteur d'activité
                                <div class="border border-1 border-green enable-flex">
                                    <input type="text" name="activite" value="<?php if(isset($medecin['activite'])) echo $medecin['activite'];?>" class="input-grow">
                                </div>
                            </span>
                            <span> Mot de passe
                                <div class="border border-1 border-green text-black">
                                    <input type="text" name="password" value="<?php if(isset($medecin['password'])) echo $medecin['password'];?>" class="input-grow">
                                </div>
                            </span>
                            <span> 
                                <?php if(isset($dateError)) echo $dateError; ?>
                                Date du début d'activité
                                <div class="border border-1 border-green enable-flex">
                                    <input type="date" name="dateDebutActivite" max="<?php echo date('Y-m-d'); ?>" value="<?php if(isset($medecin['dateDebutActivites'])) echo $medecin['dateDebutActivites']; ?>" class="input-grow">
                                </div>
                            </span>
                            <input type="hidden" name="actualLogin" value="<?php if(isset($medecin['numRPPS'])) echo $medecin['numRPPS'];?>">
                            <input type="hidden" name="action" value="<?php echo $nextAction ?>">
                            <input type="hidden" name="controller" value="administrateur">

                            <button type="submit">Valider</button>
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
