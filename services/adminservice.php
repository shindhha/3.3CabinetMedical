<?php


namespace services;

use PDOException;

/**
 *
 */
class AdminService
{

    public function getMedecinID($pdo,$numRPPS)
    {
        $sql = "SELECT id FROM Medecins where numRPPS = :numRPPS";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("numRPPS",$numRPPS);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function deleteUser($pdo,$userID)
    {
        $sql = "DELETE FROM users where id = :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("userID",$userID);
        $stmt->execute();
    }

    public function deleteMedecin($pdo,$numRPPS)
    {
        $sql = "DELETE FROM Medecins WHERE numRPPS = :numRPPS";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("numRPPS",$numRPPS);
        $stmt->execute();
    }
    public function getUserID($pdo,$login)
    {
        $sql = "SELECT id FROM users where login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("login",$login);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateUser($pdo,$idUser,$login,$password)
    {
        $sql = "UPDATE users 
                SET login = :login , `password` = MD5(:password)
                WHERE id = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("idUser",$idUser);
        $stmt->bindParam("login",$login);
        $stmt->bindParam("password",$password);
        $stmt->execute();
    }
    public function addUser($pdo,$login,$password)
    {
        $sql = "INSERT INTO users (login,`password`) VALUES (:login,MD5(:password))";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("login",$login);
        $stmt->bindParam("password",$password);
        $stmt->execute();
    }

    public function getMedecinsList($pdo)
    {
        $sql = "SELECT numRPPS, nom, prenom, dateDebutActivites, numTel FROM Medecins";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMedecin($pdo, $numRPPS)
    {
        $sql = "SELECT numRPPS, nom, prenom, adresse, numTel, email, dateInscription, dateDebutActivites, activite, codePostal, ville, lieuAct 
                FROM Medecins 
                WHERE numRPPS = :numRPPS";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('numRPPS', $numRPPS);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateMedecin($pdo,$idMedecin, $numRPPS, $nom, $prenom, $adresse, $cp, $ville, $tel, $mail, $secteurActivite, $dateDebutActivites) {
        $sql = "UPDATE Medecins 
        SET nom = :nom, 
            prenom = :prenom, 
            adresse = :adresse ,
            codePostal = :cp, 
            ville = :ville, 
            numTel = :tel, 
            email = :mail, 
            activite = :secteurActivite, 
            dateDebutActivites = :dateDebutActivites,
            numRPPS = :numRPPS 
            WHERE id = :idMedecin";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('numRPPS', $numRPPS);
        $stmt->bindParam('nom', $nom);
        $stmt->bindParam('prenom', $prenom);
        $stmt->bindParam('adresse', $adresse);
        $stmt->bindParam('cp', $cp);
        $stmt->bindParam('ville', $ville);
        $stmt->bindParam('tel', $tel);
        $stmt->bindParam('mail', $mail);
        $stmt->bindParam('secteurActivite', $secteurActivite);
        $stmt->bindParam('dateDebutActivites', $dateDebutActivites);
        $stmt->bindParam('idMedecin', $idMedecin);
        $stmt->execute();
    }

    public function createMedecin($pdo, $rpps, $nom, $prenom, $adresse, $cp, $ville, $tel, $mail, $secteurActivite, $dateDebutActivites) {
        $sql = "INSERT INTO Medecins (numRPPS, nom, prenom, adresse, codePostal, ville, numTel, email, activite, dateDebutActivites, dateInscription) VALUE (:rpps, :nom, :prenom, :adresse, :cp, :ville, :tel, :mail, :secteurActivite, :dateDebutActivites, CURDATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('rpps', $rpps);
        $stmt->bindParam('nom', $nom);
        $stmt->bindParam('prenom', $prenom);
        $stmt->bindParam('adresse', $adresse);
        $stmt->bindParam('cp', $cp);
        $stmt->bindParam('ville', $ville);
        $stmt->bindParam('tel', $tel);
        $stmt->bindParam('mail', $mail);
        $stmt->bindParam('secteurActivite', $secteurActivite);
        $stmt->bindParam('dateDebutActivites', $dateDebutActivites);
        $stmt->execute();
    }

    private static $defaultAdminService ;

    public static function getDefaultAdminService()
    {
        if (AdminService::$defaultAdminService == null) {
            AdminService::$defaultAdminService = new AdminService();
        }
        return AdminService::$defaultAdminService;
    }

}
