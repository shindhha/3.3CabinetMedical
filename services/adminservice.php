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
        $sql = "SELECT idMedecin FROM Medecins where numRPPS = :numRPPS";
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
        if (!preg_match("#[1-9]{11}#",$numRPPS)) {
            throw new PDOException("Le numéro de Répertoire Partagé des Professionnels intervenant dans le système de Santé (RPPS) n'est pas valide ! ", 1);
        }
        if ($dateDebutActivites == "") {
            throw new PDOException("Veuillez selectionner une date ! ",2);
        }
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
            WHERE idMedecin = :idMedecin";
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

    public function createMedecin($pdo, $numRPPS, $nom, $prenom, $adresse, $cp, $ville, $tel, $mail, $secteurActivite, $dateDebutActivites) {
        if (!preg_match("#[1-9]{11}#",$numRPPS)) {
            throw new PDOException("Le numéro de Répertoire Partagé des Professionnels intervenant dans le système de Santé (RPPS) n'est pas valide ! ", 1);
        }
        if ($dateDebutActivites == "") {
            throw new PDOException("Veuillez selectionner une date ! ",2);
        }
        $sql = "INSERT INTO Medecins (numRPPS, nom, prenom, adresse, codePostal, ville, numTel, email, activite, dateDebutActivites, dateInscription) VALUE (:numRPPS, :nom, :prenom, :adresse, :cp, :ville, :tel, :mail, :secteurActivite, :dateDebutActivites, CURDATE())";
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
        $stmt->execute();
    }

    public function getErreursImportShort($pdo) {
        $sql = "SELECT messageErreur, COUNT(messageErreur) as nbreErreurs FROM ErreursImportation GROUP BY messageErreur ORDER BY nbreErreurs DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
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
