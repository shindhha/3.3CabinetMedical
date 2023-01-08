<?php


namespace services;

use PDOException;

/**
 *
 */
class UsersServices
{
  public function findIfAdminExists($pdo,$username,$password)
  {
    $sql = "SELECT *
         FROM users
         WHERE login = :username AND `password` = MD5(:password)";

    $request = $pdo->prepare($sql);
    $request->execute(['username' => $username , 'password' => $password]);
    $nbRow = $request->rowcount();
    return $nbRow >= 1;
  }
  public function getVisites($pdo,$numSecu)
  {
    $sql = "SELECT motifVisite,dateVisite,note
            FROM visites
            JOIN listevisites
            ON listevisites.idVisite = visites.idVisite";
  }

  public function getListPatients($pdo,$medecinRef,$numSecu = "%")
  {
    $sql = "SELECT patients.numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email
            FROM patientsmedecins
            JOIN patients
            ON patientsmedecins.numSecu = patients.numSecu
            WHERE numRPPS = :numRPPS
            AND patients.numSecu LIKE :numSecu";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('numRPPS',$medecinRef);
    $stmt->bindParam('numSecu',$numSecu);

    $stmt->execute();

    return $stmt->fetchAll();

  }
  public function getListMedic($pdo,$formePharma = "%",$labelVoieAdministration = "%",$etatCommercialisation = -1,$tauxRemboursement = "",$prixMin = 0,$prixMax = 100000,$surveillanceRenforcee = -1, $designation = "%")
  {
    $sql = "SELECT codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,designation,surveillanceRenforcee
            FROM listMedic
            WHERE formePharma LIKE :formePharma
            AND labelVoieAdministration LIKE :labelVoieAdministration
            AND designation LIKE :designation
            AND prix > :prixMin AND prix < :prixMax
            
            ";
    $param = array('formePharma' => $formePharma,
                   'labelVoieAdministration' => $labelVoieAdministration,
                   'prixMin' => $prixMin,
                   'prixMax' => $prixMax,
                   'designation' => $designation,);

    if ($etatCommercialisation != -1) {
      $sql = $sql . " AND etatCommercialisation = :etatCommercialisation";
      $param['etatCommercialisation'] = $etatCommercialisation;
    }

    if ($tauxRemboursement != "") {
      $sql = $sql . " AND tauxRemboursement = :tauxRemboursement";
      $param['tauxRemboursement'] = $tauxRemboursement;
    }

    if ($surveillanceRenforcee != -1) {
      $sql = $sql . " AND surveillanceRenforcee = :surveillanceRenforcee";
      $param['surveillanceRenforcee'] = $surveillanceRenforcee;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($param);

    return $stmt->fetchAll();
  }

  public function getparams($pdo,$param,$table)
  {
    $sql = "SELECT DISTINCT(" . $param .")"
           . " FROM " . $table;

    return $pdo->query($sql);
  }

  private static $defaultUsersService ;

  public static function getDefaultUsersService()
  {
      if (UsersServices::$defaultUsersService == null) {
          UsersServices::$defaultUsersService = new UsersServices();
      }
      return UsersServices::$defaultUsersService;
  }
}
