<?php

namespace controllers;

use yasmf\View;
use services\UsersServices;
use yasmf\HttpHelper;

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
		$numSecu = HttpHelper::getParam("numSecu");
		$this->usersservices->deletePatient($pdo,$numSecu);
		return $this->index($pdo);
		
	}

	

	public function goFichePatient($pdo)
	{
		$view = new View("Sae3.3CabinetMedical/views/patient");
		if (HttpHelper::getParam("numSecu") != "") {
			$_SESSION['patient'] = HttpHelper::getParam("numSecu");
		}
		
		$visites = $this->usersservices->getVisites($pdo,$_SESSION['patient']);
		$patient = $this->usersservices->getPatient($pdo,$_SESSION['medecin'],$_SESSION['patient']);
		$view->setVar("visites",$visites);
		$view->setVar("patient",$patient);
		return $view;
	}

	public function addPatient($pdo)
	{
		if (HttpHelper::getParam("numSecu") != "") {
			$_SESSION['patient'] = HttpHelper::getParam("numSecu");
		}
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


		$this->usersservices->insertPatient($pdo,$_SESSION['patient'],$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
		return $this->goFichePatient($pdo);
	}

	public function updatePatient($pdo)
	{	
		if (HttpHelper::getParam("numSecu") != "") {
			$_SESSION['patient'] = HttpHelper::getParam("numSecu");
		}

		$actualNumSecu = HttpHelper::getParam("actualNumSecu");

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
		$patientID = $this->usersservices->getPatientID($pdo,$actualNumSecu);

		$this->usersservices->updatePatient($pdo,$patientID['id'],$_SESSION['patient'],$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
		return $this->goFichePatient($pdo);

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

	public function goEditPatient($pdo)
	{
		$view = new View("Sae3.3CabinetMedical/views/editPatient");
		$nextAction = HttpHelper::getParam('nextAction');
		$medecins = $this->usersservices->getMedecins($pdo);
		if ($nextAction == "updatePatient") {
			$patient = $this->usersservices->getPatient($pdo,$_SESSION['medecin'],$_SESSION['patient']);
			$view->setVar("patient",$patient);
		}
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
		$patient = $this->usersservices->getPatient($pdo,$_SESSION['id'],$_SESSION['patient']);
		$visite = $this->usersservices->getVisite($pdo,$_SESSION['patient'],$_SESSION['idVisite']);
		$view->setVar("idVisite",$_SESSION['idVisite']);
		$view->setVar("visite",$visite);
		$view->setVar("drugsVisite",$drugsVisite);
		$view->setVar("patient",$patient);
		return $view;
	}

	public function goEditVisite($pdo)
	{
		$view = new View("Sae3.3CabinetMedical/views/editVisite");

		$nextAction = HttpHelper::getParam("nextAction");
		if ($nextAction == "updateVisite") {
			$visite = $this->usersservices->getVisites($pdo,$_SESSION['patient'],$_SESSION['idVisite']);
			$view->setVar("visite",$visite);
		}

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
		$motif = HttpHelper::getParam("Motif");
		$Date = HttpHelper::getParam("Date");
		$Description = HttpHelper::getParam("Description");
		$Conclusion = HttpHelper::getParam("Conclusion");
		$this->usersservices->modifVisite($pdo,$_SESSION['idVisite'],$motif,$Date,$Description,$Conclusion);
		return $this->goFicheVisite($pdo);
	}

	public function addVisite($pdo)
	{

		$motif = HttpHelper::getParam("Motif");
		$Date = HttpHelper::getParam("Date");
		$Description = HttpHelper::getParam("Description");
		$Conclusion = HttpHelper::getParam("Conclusion");
		$_SESSION['idVisite'] =  $this->usersservices->insertVisite($pdo,$_SESSION['patient'],$motif,$Date,$Description,$Conclusion);
		return $this->goFicheVisite($pdo);
	}

	public function addMedicament($pdo)
	{	
		$codeCIS = HttpHelper::getParam("codeCIS");
		$instruction = HttpHelper::getParam("instruction");
		$this->usersservices->addMedic($pdo,(int) $_SESSION['idVisite'],(int) $codeCIS,$instruction);
		return $this->goFicheVisite($pdo);
	}

	public function editInstruction($pdo)
	{
		$codeCIS = HttpHelper::getParam("codeCIS");
		$instruction = HttpHelper::getParam("instruction");
		$this->usersservices->editInstruction($pdo,$_SESSION['idVisite'],$codeCIS,$instruction);
		return $this->goFicheVisite($pdo);
	}

}
