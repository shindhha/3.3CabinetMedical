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
      $action = HttpHelper::getParam("Valider");
      if ($action != null) {
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
        $this->usersservices->insertPatient($pdo,$_SESSION['id'],$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email);
      }

      $numSecu = HttpHelper::getParam("numSecu");
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




      return $view;
    }

    public function visite($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/visite");
      $_SESSION['idVisite'] = HttpHelper::getParam("idVisite");
      $patient = $this->usersservices->getListPatients($pdo,$_SESSION['id'],$_SESSION['patient']);
      $drugs = $this->usersservices->getOrdonnances($pdo,$_SESSION['idVisite']);
      $view->setVar("drugs",$drugs);
      $view->setVar("patient",$patient);
      return $view;
    }

}
