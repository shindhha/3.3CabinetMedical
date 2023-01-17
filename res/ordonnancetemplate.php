<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ordonnance</title>
  <link href="/Sae3.3CabinetMedical/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/Sae3.3CabinetMedical/css/style_ordonnance.css" rel="stylesheet" type="text/css">
</head>
<body>

<div style="width: 100%;" class="border-bottom">
    <div align="left" style="width: 50%;float: left;">
        <p>
            Dr. <?php echo $medecin['nom'] . " " . $medecin['prenom'] ?><br>
            <?php echo $medecin['activite'] ?><br>
            <?php echo wordwrap("0".$medecin['numTel'], 2, '.', true) ?>
        </p>
    </div>

    <div align="left" style="width: 50%;float: left;">
        <br>
        <?php echo $cabinet['adresse'] ?><br>
        <?php echo $cabinet['codePostal'] . " " . $cabinet['ville']?> <br>
    </div>
</div>


<div style="width: 100%;" class="mt-5">
    <div align="left" style="width: 50%;float: left;">
        <span class="fw-bold"><?php echo $patient['nom'] . " " . $patient['prenom'] ?></span><br>
        <?php echo $patient['adresse'] ?><br>
        <?php echo $patient['codePostal'] . " " . $patient['ville']?><br>
        <?php echo wordwrap("0".$patient['numTel'], 2, '.', true)?>
    </div>

    <div align="left" style="width: 50%;float: left;">
        <br>
        <br>
        <br>
        A <?php echo $cabinet['ville'] ?>, le <?php echo date("d/m/Y") ?>
    </div>
</div>

<div style="width: 100%" class="mt-5 pt-5">
    <?php
    foreach ($medicaments as $medicament) {
        echo "<div class='pb-4'>";
            echo "<span class='text-decoration-underline'>" . $medicament['designation'] . "</span><br>";
            echo " (" . $medicament['libellePresentation'] . ")" . '<br>';
            echo " - " . $medicament['instruction'] . "<br>";
        echo "</div>";
        }
    ?>
</div>

  <div class="row">
    <div class="offset-7 fixed-bottom pb-5 mb-5">
      <span class="text-decoration-underline">Signature : </span>
    </div>
  </div>

</body>
</html>