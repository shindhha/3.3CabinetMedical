<?php


namespace services;

use PDOException;

/**
 *
 */
class AdminService
{
    private static $defaultAdminService ;

    public static function getDefaultAdminService()
    {
        if (AdminService::$defaultAdminService == null) {
            AdminService::$defaultAdminService = new AdminService();
        }
        return AdminService::$defaultAdminService;
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
        $sql = "SELECT numRPPS, nom, prenom, adresse, numTel, email, dateInscription, dateDebutActivites, activite, codePostal, ville, lieuAct FROM Medecins WHERE numRPPS = :numRPPS";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('numRPPS', $numRPPS);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getNewMedecin()
    {
        return array(
            'numRPPS' => '',
            'nom' => '',
            'prenom' => '',
            'adresse' => '',
            'codePostal' => '',
            'ville' => '',
            'numTel' => '',
            'email' => '',
            'activite' => '',
            'dateDebutActivites' => '',
            'dateInscription' => date('Y-m-d')
        );
    }

    public function updateMedecin($pdo, $rpps, $nom, $prenom, $adresse, $cp, $ville, $tel, $mail, $secteurActivite, $dateDebutActivites) {
        $sql = "UPDATE Medecins SET nom = :nom, prenom = :prenom, adresse = :adresse ,codePostal = :cp, ville = :ville, numTel = :tel, email = :mail, activite = :secteurActivite, dateDebutActivites = :dateDebutActivites WHERE numRPPS = :rpps";
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

}
