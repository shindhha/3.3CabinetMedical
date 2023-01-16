<?php

namespace controllers;

use yasmf\View;
use services\UsersServices;
use yasmf\HttpHelper;
use PDOException;

/**
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2019   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

class PatientsListController
{
	private $usersservices;
	public function __construct()
	{
		$this->usersservices = UsersServices::getDefaultUsersService();
	}
	public function index($pdo) {
		$view = new View("Sae3.3CabinetMedical/views/patientslist");
		HttpHelper::getParam('controller') ?: 'Connection';

		$textInput = HttpHelper::getParam("search")? explode(" ", HttpHelper::getParam("search")) : "%"; 
		$nom = $textInput[0]?: "";
		$prenom = isset($textInput[1]) == true ? $textInput[1] : $textInput[0];
		$medecinTraitant = HttpHelper::getParam("medecin")?: "%";

		$patients = $this->usersservices->getListPatients($pdo,$medecinTraitant,$nom."%",$prenom."%");
		$medecin = $this->usersservices->getMedecins($pdo);
		$view->setVar("medecin",$medecin);
		$view->setVar("patients",$patients);
		return $view;
	}

	public function deletePatient($pdo)
	{
		$idPatient = HttpHelper::getParam("idPatient");
		$this->usersservices->deletePatientFrom($pdo,"ListeVisites",$idPatient);
		$this->usersservices->deletePatientFrom($pdo,"Patients",$idPatient);
		return $this->index($pdo);
		
	}

	

	public function goFichePatient($pdo)
	{
		$view = new View("Sae3.3CabinetMedical/views/patient");
		if (HttpHelper::getParam("idPatient") !== null) {
			$_SESSION['patient'] = HttpHelper::getParam("idPatient");
		}
		
		$visites = $this->usersservices->getVisites($pdo,$_SESSION['patient']);
		$patient = $this->usersservices->getPatient($pdo,$_SESSION['patient']);
		$view->setVar("visites",$visites);
		$view->setVar("patient",$patient);
		return $view;
	}

	public function addPatient($pdo)
	{
		$_SESSION['patient'] = "";
		$numSecu = HttpHelper::getParam("numSecu");
		$view;
		$nom = HttpHelper::getParam("nom");
		$prenom = HttpHelper::getParam("prenom");
		$adresse = HttpHelper::getParam("adresse");
		$numTel = HttpHelper::getParam("numTel");
		$email = HttpHelper::getParam("email");
		$medecinRef = HttpHelper::getParam("medecinRef");
		$dateNaissance = HttpHelper::getParam("dateNaissance");
		$LieuNaissance = HttpHelper::getParam("LieuNaissance");
		$notes = HttpHelper::getParam("notes");
		$codePostal = HttpHelper::getParam("codePostal");
		$sexe = (int) HttpHelper::getParam("sexe");

		try {
			$_SESSION['patient'] = $this->usersservices->insertPatient($pdo,$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
			$view = $this->goFichePatient($pdo);

		} catch (PDOException $e) {
			$view = $this->goEditPatient($pdo,"addPatient");
			if ($e->getCode() == "23000") {
				$view->setVar("numSecuError","Ce numéro de sécurité sociale est déjà utilisé ! ");
			}
			if ($e->getCode() == "HY000") {
				$view->setVar("emailError","L'adresse mail n'est pas valide ! ");
			}
			if ($e->getCode() == "1") {
				$view->setVar("numSecuError",$e->getMessage());
			}
		}
		
		
		return $view;
	}

	public function updatePatient($pdo)
	{	

		$numSecu = HttpHelper::getParam("numSecu");
		

		$view;
		$nom = HttpHelper::getParam("nom");
		$prenom = HttpHelper::getParam("prenom");
		$adresse = HttpHelper::getParam("adresse");
		$numTel = HttpHelper::getParam("numTel");
		$email = HttpHelper::getParam("email");
		$medecinRef = HttpHelper::getParam("medecinRef");
		$dateNaissance = HttpHelper::getParam("dateNaissance");
		$LieuNaissance = HttpHelper::getParam("LieuNaissance");
		$notes = HttpHelper::getParam("notes");
		$codePostal = HttpHelper::getParam("codePostal");
		$sexe = (int) HttpHelper::getParam("sexe");
		try {
			$this->usersservices->updatePatient($pdo,$_SESSION['patient'],$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
			$view = $this->goFichePatient($pdo);
		} catch (PDOException $e) {
			$view = $this->goEditPatient($pdo,"updatePatient");
			if ($e->getCode() == "23000") {
				$view->setVar("numSecuError","Ce numéro de sécurité sociale est déjà utilisé ! ");
			}
			if ($e->getCode() == "HY000") {
				$view->setVar("emailError","L'adresse mail n'est pas valide ! ");
			}
			if ($e->getCode() == "1") {
				$view->setVar("numSecuError",$e->getMessage());
			}
		}
		
		return $view;

	}

	public function deleteVisite($pdo)
	{	
		if (HttpHelper::getParam("idVisite") != "") {
			$_SESSION['idVisite'] = HttpHelper::getParam("idVisite");
		}
		$this->usersservices->deleteVisiteFrom($pdo,"Ordonnances",$_SESSION['idVisite']);
		$this->usersservices->deleteVisiteFrom($pdo,"ListeVisites",$_SESSION['idVisite']);
		$this->usersservices->deleteVisiteFrom($pdo,"Visites",$_SESSION['idVisite']);

		return $this->goFichePatient($pdo);
	}

	public function goEditPatient($pdo,$action = "")
	{
		$view = new View("Sae3.3CabinetMedical/views/editPatient");
		$patient;
		if ($action == "addPatient") {
			$patient['numSecu'] = HttpHelper::getParam("numSecu");
			$patient['LieuNaissance'] = HttpHelper::getParam("LieuNaissance");
			$patient['nom'] = HttpHelper::getParam("nom");
			$patient['prenom'] = HttpHelper::getParam("prenom");
			$patient['dateNaissance'] = HttpHelper::getParam("dateNaissance");
			$patient['adresse'] = HttpHelper::getParam("adresse");
			$patient['codePostal'] = HttpHelper::getParam("codePostal");
			$patient['medecinRef'] = HttpHelper::getParam("medecinRef");
			$patient['numTel'] = HttpHelper::getParam("numTel");
			$patient['email'] = HttpHelper::getParam("email");
			$patient['sexe'] = HttpHelper::getParam("sexe");
			$patient['notes'] = HttpHelper::getParam("notes");
		} else {
			$patient = $this->usersservices->getPatient($pdo,$_SESSION['patient']);
		}
		
		$nextAction = HttpHelper::getParam('nextAction')?: $action;
		$medecins = $this->usersservices->getMedecins($pdo);
		
		$view->setVar("patient",$patient);
		
		$view->setVar("medecins",$medecins);      
		$view->setVar("action",$nextAction);
		return $view;
	}

	public function goFicheVisite($pdo)
	{
		$view = new View("Sae3.3CabinetMedical/views/visite");
		if (HttpHelper::getParam("idVisite") !== null) {
			$_SESSION['idVisite'] = HttpHelper::getParam("idVisite");
		}
		$drugsVisite = $this->usersservices->getOrdonnances($pdo,$_SESSION['idVisite']);
		$patient = $this->usersservices->getPatient($pdo,$_SESSION['patient']);
		$visite = $this->usersservices->getVisite($pdo,$_SESSION['idVisite']);
		$view->setVar("idVisite",$_SESSION['idVisite']);
		$view->setVar("visite",$visite);
		$view->setVar("drugsVisite",$drugsVisite);
		$view->setVar("patient",$patient);
		return $view;
	}

	public function goEditVisite($pdo,$action = "")
	{
		$view = new View("Sae3.3CabinetMedical/views/editVisite");
		$visite;
		if ($action == "addVisite") {
			$visite['motifVisite'] = HttpHelper::getParam("motifVisite");
			$visite['dateVisite'] = HttpHelper::getParam("dateVisite");
			$visite['Description'] = HttpHelper::getParam("Description");
			$visite['Conclusion'] = HttpHelper::getParam("Conclusion");
		} else {
			$visite = $this->usersservices->getVisite($pdo,$_SESSION['idVisite']);
		}

		$nextAction = HttpHelper::getParam("nextAction")?: $action;
		$view->setVar("visite",$visite);
		$view->setVar("action",$nextAction);

		return $view;
	}

	public function deleteMedicament($pdo)
	{
		$codeCIS = HttpHelper::getParam("codeCIS");
		$this->usersservices->deleteMedicament($pdo,$_SESSION['idVisite'],$codeCIS);
		return $this->goFicheVisite($pdo);
	}

	public function updateVisite($pdo)
	{
		$view;
		$motif = HttpHelper::getParam("motifVisite");
		$Date = HttpHelper::getParam("Date");
		$Description = HttpHelper::getParam("Description");
		$Conclusion = HttpHelper::getParam("Conclusion");
		
		try {
			$this->usersservices->modifVisite($pdo,$_SESSION['idVisite'],$motif,$Date,$Description,$Conclusion);
			$view = $this->goFicheVisite($pdo);
		} catch (PDOException $e) {
			$view = $this->goEditVisite($pdo,"updateVisite");
			if ($e->getCode() == "22007") {
				$view->setVar("dateError","Veuillez sélectionner la date.");
			}
		}
		
		return $view;
	}

	public function addVisite($pdo)
	{
		$view;
		$motif = HttpHelper::getParam("motifVisite");
		$Date = HttpHelper::getParam("Date");
		$Description = HttpHelper::getParam("Description");
		$Conclusion = HttpHelper::getParam("Conclusion");
		
		try {
			$_SESSION['idVisite'] = $this->usersservices->insertVisite($pdo,$_SESSION['patient'],$motif,$Date,$Description,$Conclusion);
			$view = $this->goFicheVisite($pdo);
		} catch (PDOException $e) {
			$view = $this->goEditVisite($pdo,"addVisite");
			if ($e->getCode() == "22007") {
				$view->setVar("dateError","Veuillez sélectionner la date.");
			}
		}
		
		return $view;
	}

	public function addMedicament($pdo)
	{	
		$codeCIS = HttpHelper::getParam("codeCIS");
		$instruction = HttpHelper::getParam("instruction");

		$view;
		try {
			$this->usersservices->addMedic($pdo,$_SESSION['idVisite'],(int) $codeCIS,$instruction);
			$view = $this->goFicheVisite($pdo);
		} catch (PDOException $e) {
			$view = $this->goFicheVisite($pdo);
			$view->setVar("addMedicError","Ce médicament a déjà été ajouté !");
		}

		
		return $view;
	}

	public function editInstruction($pdo)
	{
		$codeCIS = HttpHelper::getParam("codeCIS");
		$instruction = HttpHelper::getParam("instruction");
		$this->usersservices->editInstruction($pdo,$_SESSION['idVisite'],$codeCIS,$instruction);
		return $this->goFicheVisite($pdo);
	}

}
