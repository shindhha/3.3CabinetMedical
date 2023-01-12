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

        $search = HttpHelper::getParam("search") != "" ? HttpHelper::getParam("search") : "%";

        $medecinTraitant = HttpHelper::getParam("medecin");

        $patients = $this->usersservices->getListPatients($pdo,$_SESSION['medecin'],$medecinTraitant,$search."%");
        $medecin = $this->usersservices->getMedecins($pdo);

        $view->setVar("medecin",$medecin);
        $view->setVar("patients",$patients);
        return $view;
    }

    public function fichePatient($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/patient");
      $_SESSION['idVisite'] = HttpHelper::getParam("idVisite");

      if (HttpHelper::getParam("numSecu") != "") {
        $_SESSION['patient'] = HttpHelper::getParam("numSecu");
      }

      



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
      $patient = $this->usersservices->getPatient($pdo,$_SESSION['medecin'],$_SESSION['patient']);

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
      if (HttpHelper::getParam("idVisite") !== null) {
        $_SESSION['idVisite'] = HttpHelper::getParam("idVisite");
      }

      $codeCIS = HttpHelper::getParam("codeCIS");
      $instruction = HttpHelper::getParam("instruction");
      try {
        if ($codeCIS != "") {
          $this->usersservices->addMedic($pdo,(int) $_SESSION['idVisite'],(int) $codeCIS,$instruction);
        }
      } catch (PDOException $e) {
      }
      


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
      $valeurASMR = $this->usersservices->getparams($pdo,"valeurASMR","cis_has_asmr");
      $formePharmas = $this->usersservices->getparams($pdo,"formePharma","FormePharma");
      $voieAdministration = $this->usersservices->getparams($pdo,"labelVoieAdministration","ID_Label_VoieAdministration");
      $niveauSmr = $this->usersservices->getparams($pdo,"libelleNiveauSMR","niveauSmr");
      $tauxRemboursements = $this->usersservices->getparams($pdo,"tauxRemboursement","TauxRemboursement");
      $voieAdministration = $this->usersservices->getparams($pdo,"labelVoieAdministration","ID_Label_VoieAdministration");

      $pformePharma = HttpHelper::getParam("pformePharma") !== null ? HttpHelper::getParam("pformePharma") : "%" ;
      $pVoieAdmi = HttpHelper::getParam("pVoieAdmi") !== null ? HttpHelper::getParam("pVoieAdmi") : "%" ;
      $pTauxRem = HttpHelper::getParam("pTauxRem") !== null ? HttpHelper::getParam("pTauxRem") : "";
      $pPrixMin = (int) HttpHelper::getParam("pPrixMin");
      $pPrixMax = (int) HttpHelper::getParam("pPrixMax") == 0 ? 10000 : (int) HttpHelper::getParam("pPrixMax");
      $pEtat =  HttpHelper::getParam("pEtat") !== null ? (int) HttpHelper::getParam("pEtat") : -1;
      $pSurveillance = HttpHelper::getParam("pSurveillance") !== null ? (int) HttpHelper::getParam("pSurveillance") : -1;
      $pNiveauSmr = HttpHelper::getParam("pNiveauSmr") !== null ?  HttpHelper::getParam("pNiveauSmr") : "%";
      $pValeurASMR = HttpHelper::getParam("pValeurASMR") !== null ?  HttpHelper::getParam("pValeurASMR") : "%";
      $pPresentation = HttpHelper::getParam("pPresentation");
      $drugsVisite = $this->usersservices->getOrdonnances($pdo,$_SESSION['idVisite']);
      $drugs = $this->usersservices->getListMedic($pdo,$pformePharma,$pVoieAdmi,$pEtat,$pTauxRem,$pPrixMin,$pPrixMax,$pSurveillance,$pValeurASMR,$pNiveauSmr,"%" . $pPresentation . "%");
      $patient = $this->usersservices->getListPatients($pdo,$_SESSION['id'],$_SESSION['patient']);
      $visite = $this->usersservices->getVisites($pdo,$_SESSION['patient'],$_SESSION['idVisite']);
      $view->setVar("idVisite",$_SESSION['idVisite']);
      $view->setVar("niveauSmr",$niveauSmr);
      $view->setVar("valeurASMR",$valeurASMR);
      $view->setVar("voieAd",$voieAdministration);
      $view->setVar("tauxRemboursements",$tauxRemboursements);
      $view->setVar("formePharmas",$formePharmas);
      $view->setVar("modif",$modif);
      $view->setVar("visite",$visite);
      $view->setVar("drugs",$drugs);
      $view->setVar("drugsVisite",$drugsVisite);
      $view->setVar("patient",$patient);
      $view->setVar("pEtat",$pEtat);
      $view->setVar("pSurveillance",$pSurveillance);
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
