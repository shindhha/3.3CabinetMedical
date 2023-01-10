<?php
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

        $patients = $this->usersservices->getListPatients($pdo,$_SESSION['medecin']);



        $view->setVar("patients",$patients);
        return $view;
    }

    public function fichePatient($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/patient");
      $_SESSION['idVisite'] = HttpHelper::getParam("idVisite");
      $_SESSION['patient'] = HttpHelper::getParam("numSecu");



      $modif = HttpHelper::getParam("modif");
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


     
        if ($modif == "Ajouter") {
        
          $this->usersservices->insertPatient($pdo,$_SESSION['medecin'],$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
        }

        if ($modif == "Modifier") {
          $this->usersservices->updatePatient($pdo,$_SESSION['patient'],$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
        }

        if ($modif == "Supprimer") {
          $this->usersservices->deleteVisite($pdo,$_SESSION['idVisite']);
        }

      

      
      $visites = $this->usersservices->getVisites($pdo,$_SESSION['patient']);
      $patient = $this->usersservices->getListPatients($pdo,$_SESSION['medecin'],$_SESSION['patient']);

      $view->setVar("visites",$visites);
      $view->setVar("patient",$patient);
      return $view;
    }


    public function modifPatient($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/modifPatient");

      $modif = HttpHelper::getParam("modif");
      $medecins = $this->usersservices->getMedecins($pdo);
      if ($modif == "Modifier") {
        $patient = $this->usersservices->getListPatients($pdo,$_SESSION['medecin'],$_SESSION['patient']);
        $view->setVar("patient",$patient);
      }

      

      $view->setVar("medecins",$medecins);      
      $view->setVar("modif",$modif);
      



      return $view;
    }

    public function deletePatient($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/patientslist");
      $numSecu = HttpHelper::getParam("numSecu");
      $this->usersservices->deletePatient($pdo,$numSecu);
      $patients = $this->usersservices->getListPatients($pdo,$_SESSION['medecin']);
      $view->setVar("patients",$patients);
      return $view;
    }

    public function visite($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/visite");
      $_SESSION['idVisite'] = HttpHelper::getParam("idVisite");
      $modif = HttpHelper::getParam("modif");
      $motif = HttpHelper::getParam("Motif");
      $Date = HttpHelper::getParam("Date");
      $Description = HttpHelper::getParam("Description");
      $Conclusion = HttpHelper::getParam("Conclusion");

      if ($modif == "Ajouter") {
       $_SESSION['idVisite'] =  $this->usersservices->insertVisite($pdo,$_SESSION['patient'],$motif,$Date,$Description,$Conclusion);
      }

      if ($modif == "Modifier") {
        $this->usersservices->modifVisite($pdo,$_SESSION['idVisite'],$motif,$Date,$Description,$Conclusion);
      }

      

      $drugs = $this->usersservices->getOrdonnances($pdo,$_SESSION['idVisite']);
      $patient = $this->usersservices->getListPatients($pdo,$_SESSION['id'],$_SESSION['patient']);
      $visite = $this->usersservices->getVisites($pdo,$_SESSION['patient'],$_SESSION['idVisite']);
      $view->setVar("modif",$modif);
      $view->setVar("visite",$visite);
      $view->setVar("drugs",$drugs);
      $view->setVar("patient",$patient);
      return $view;
    }

    public function addVisite($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/modifVisite");

      $modif = HttpHelper::getParam("modif");
      if ($modif == "Modifier") {
        $visite = $this->usersservices->getVisites($pdo,$_SESSION['patient'],$_SESSION['idVisite']);
        $view->setVar("visite",$visite);
      }
      
      $view->setVar("modif",$modif);

      return $view;
    }

}
