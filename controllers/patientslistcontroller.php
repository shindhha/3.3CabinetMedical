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

        $patients = $this->usersservices->getListPatients($pdo,$_SESSION['id']);



        $view->setVar("patients",$patients);
        return $view;
    }

    public function fichePatient($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/patient");
      $modif = HttpHelper::getParam("modif");
      $nom = HttpHelper::getParam("nom");
      $prenom = HttpHelper::getParam("prenom");
      $adresse = HttpHelper::getParam("adresse");
      $numTel = HttpHelper::getParam("numTel");
      $email = HttpHelper::getParam("email");
      $medecinRef = HttpHelper::getParam("medecinRef");
      $numSecu = HttpHelper::getParam("numSecu");
      $dateNaissance = HttpHelper::getParam("dateNaissance");
      $LieuNaissance = HttpHelper::getParam("LieuNaissance");
      $notes = HttpHelper::getParam("notes");
      $codePostal = HttpHelper::getParam("codePostal");
      $sexe = (int) HttpHelper::getParam("sexe");


      try {
        if ($modif == "Ajouter") {
        
          $this->usersservices->insertPatient($pdo,$_SESSION['id'],$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
        }

        if ($modif == "Modifier") {
          $this->usersservices->updatePatient($pdo,$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes);
        }
      } catch (Exception $e) {
        
      }

      


      $_SESSION['patient'] = $numSecu;
      $visites = $this->usersservices->getVisites($pdo,$numSecu);
      $patient = $this->usersservices->getListPatients($pdo,$_SESSION['id'],$numSecu);

      $view->setVar("numSecu",$numSecu);
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
        $patient = $this->usersservices->getListPatients($pdo,$_SESSION['id'],$_SESSION['patient']);
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
      $patients = $this->usersservices->getListPatients($pdo,$_SESSION['id']);
      $view->setVar("patients",$patients);
      return $view;
    }

    public function visite($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/visite");
      $_SESSION['idVisite'] = HttpHelper::getParam("idVisite");
      $patients = $this->usersservices->getListPatients($pdo,$_SESSION['id']);
      $drugs = $this->usersservices->getOrdonnances($pdo,$_SESSION['idVisite']);
      $view->setVar("drugs",$drugs);
      $view->setVar("patients",$patients);
      return $view;
    }

}
