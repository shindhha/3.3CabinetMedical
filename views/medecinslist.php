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
                        <span class="h1"> Liste médecins </span>
                        <form>
                                <input type="hidden" name="action" value="deconnexion">
                                <input type="submit" class="btn btn-danger" value="Deconnexion">
                            </form>
                    </div>
                </nav>

            <!-- content -->
            <div class="row h-100 align-items-center text-center">
                <!-- Portail de connexion -->
                <div class="container ">
                    <div class="row justify-content-center">
                        <div class=" col-md-10 col-xl-12 col-sm-7 col-12 success border-2">
                            <div class="overflow-scroll ">
                                <table class="table table-striped lightGreen table-hover">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Exerce depuis</th>
                                        <th>Téléphone</th>
                                        <th></th>
                                    </tr>
                                    <?php
                                    foreach ($medecinsList as $row) {
                                        echo "<tr>"
                                            ."<td>" . $row['nom'] . "</td>"
                                            ."<td>" . $row['prenom'] . "</td>"
                                            ."<td>" . $row['dateDebutActivites'] . "</td>"
                                            ."<td>0" . $row['numTel'] . "</td>" // 0 pour le formatage (05.XX...)
                                        ?>
                                        <td>
                                        <div class="dropdown">
                                            <span class="material-symbols-outlined" type="button" id="dropdownMenuButton1" data-bs-auto-close="false" data-bs-toggle="dropdown" aria-expanded="false">
                                                more_horiz
                                            </span>
                                            <div class="p-0  dropdown-menu dropdown-menu-end green text-white no-border" aria-labelledby="dropdownMenuButton1">
                                                <table class="text-white ">
                                                    <form action="index.php" action="POST" class="d-flex flex-column green">
                                                        <input type="hidden" name="controller" value="administrateur">
                                                        <input type="hidden" name="action" value="goFicheMedecin">
                                                        <input type="hidden" name="idMedecin" value="<?php echo $row['idMedecin'] ?>">
                                                        <tr><td><input type="submit" name="modif" value="Afficher"> </td></tr>
                                                    </form>
                                                    <form action="index.php" action="POST" class="d-flex flex-column green">
                                                        <input type="hidden" name="controller" value="administrateur">
                                                        <input type="hidden" name="action" value="deleteMedecin">
                                                        <input type="hidden" name="idMedecin" value="<?php echo $row['idMedecin'] ?>">
                                                        <input type="hidden" name="idUser" value="<?php echo $row['idUser'] ?>">

                                                        <tr><td><input type="submit"  value="Supprimer"> </td></tr>
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
            <div class="d-flex flex-row justify-content-between float-end">
                <div class="d-flex me-2 py-2 px-3 border-1 green">
                    <form action="index.php" method="post">
                        <input type="hidden" name="nextAction" value="addMedecin">
                        <input type="hidden" name="action" value="goEditMedecin">
                        <input type="hidden" name="controller" value="administrateur">
                        <input type="submit" class="green no-border text-white" value="Ajouter un médecin">
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script type="text/javascript" src="scripts/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>
