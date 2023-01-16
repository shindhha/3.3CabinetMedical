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
use services\usersServices;
use services\ImportService;
use services\AdminService;
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

class AdministrateurController
{
	private $importservice;
    private $adminservice;
	private $files = [["CIS_bdpm.txt","BDPM",12,false,0,"BDPM","CIS_"],
					  ["CIS_CIP_bdpm.txt","CIP",13,false,0,"CIP_BDPM","CIS_"],
					  ["CIS_COMPO_bdpm.txt","COMPO",8,true,0,"COMPO","CIS_"],
					  ["CIS_HAS_SMR_bdpm.txt","SMR",6,false,0,"HAS_SMR","CIS_"],
					  ["CIS_HAS_ASMR_bdpm.txt","ASMR",6,false,0,"HAS_ASMR","CIS_"],
					  ["HAS_LiensPageCT_bdpm.txt","CT",2,false,0,"HAS_LiensPageCT",""],
					  ["CIS_GENER_bdpm.txt","GENER",5,true,2,"GENER","CIS_"],
					  ["CIS_CPD_bdpm.txt","CPD",2,false,0,"CPD","CIS_"],
					  ["CIS_InfoImportantes_bdpm.txt","INFO",4,false,0,"INFO","CIS_"]];

	public function __construct() {
		$this->importservice = ImportService::getDefaultImportService();
        $this->adminservice = AdminService::getDefaultAdminService();
	}

	public function index($pdo) {      

		$view = new View("Sae3.3CabinetMedical/views/administrateur");
	  
		return $view;
	}

	public function importAll($pdo) {
		
		foreach ($this->files as $file) {
			$fileName = $file[0];
			$sqlFunction = $file[1];
			$nbParam = $file[2];
			$trimLine = $file[3];
			$this->importservice->download($fileName);
			$stmt = $this->importservice->constSQL($pdo,$nbParam,$sqlFunction);
			$this->importservice->imports($stmt,$fileName,$trimLine);
		}

		$view = new View("Sae3.3CabinetMedical/views/administrateur");

		return $view;
	}

	public function tryToImport($pdo) {

		$view = new View("Sae3.3CabinetMedical/views/administrateur");
        foreach ($this->files as $file) {
            $filep = $file[0];
            $function = $file[1];
            $nbParam = $file[2];
            $trimLine = $file[3];
            $iCis = $file[4];
            $bd = $file[5];
            $prefixe = $file[6];
            $this->importservice->download($filep);
            try {
                $importStmt = $this->importservice->constructSQL($pdo,$nbParam,$function,true);
                $updateStmt = $this->importservice->constructSQL($pdo,$nbParam,$function,false);
                
                $test = $this->importservice->exportToBD($pdo,$importStmt,$updateStmt,$file);
                
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
                
            }
        }
		return $view;
	}

    public function deleteMedecin($pdo)
    {
        $numRPPS = HttpHelper::getParam("numRPPS");
        $userID = $this->adminservice->getUserID($pdo,$numRPPS);
        $this->adminservice->deleteUser($pdo,$userID['id']);
        $this->adminservice->deleteMedecin($pdo,$numRPPS);
        return $this->goListMedecins($pdo);
    }

    public function goListMedecins($pdo) {

        $view = new View("Sae3.3CabinetMedical/views/medecinslist");
        $view->setVar("medecinsList", $this->adminservice->getMedecinsList($pdo));
        return $view;
    }

    public function goEditMedecin($pdo,$action = "")
    {
        $view = new View("Sae3.3CabinetMedical/views/editmedecin");
        $medecin;
        if ($action == "addMedecin") {
            $medecin['numRPPS'] = HttpHelper::getParam("numRPPS");
            $medecin['nom'] = HttpHelper::getParam("nom");
            $medecin['prenom'] = HttpHelper::getParam("prenom");
            $medecin['adresse'] = HttpHelper::getParam("adresse");
            $medecin['numTel'] = HttpHelper::getParam("numTel");
            $medecin['email'] = HttpHelper::getParam("email");
            $medecin['dateDebutActivites'] = HttpHelper::getParam("dateDebutActivite");
            $medecin['activite'] = HttpHelper::getParam("activite");
            $medecin['codePostal'] = HttpHelper::getParam("codePostal");
            $medecin['ville'] = HttpHelper::getParam("ville");
            $medecin['lieuAct'] = HttpHelper::getParam("lieuAct");
        } else {
            $numRPPS = HttpHelper::getParam('numRPPS');
            $medecin = $this->adminservice->getMedecin($pdo,$numRPPS);
            
        }
        $view->setVar("medecin",$medecin);
        $nextAction = HttpHelper::getParam("nextAction")?: $action;
        $view->setVar("nextAction",$nextAction);
        
        
        return $view;
    }

    public function goFicheMedecin($pdo)
    {
        $view = new View("Sae3.3CabinetMedical/views/medecin");
        $view->setVar("medecin", $this->adminservice->getMedecin($pdo, HttpHelper::getParam('numRPPS')));
        return $view;
    }

    public function updateMedecin($pdo) {
        $view;
        $numRPPS = HttpHelper::getParam('numRPPS');
        $password = HttpHelper::getParam('password');
        $actualLogin = HttpHelper::getParam("actualLogin");
        try {
            $medecinID = $this->adminservice->getMedecinID($pdo,$actualLogin);
            $this->adminservice->updateMedecin($pdo,$medecinID['id'],$numRPPS ,
                HttpHelper::getParam('nom'),
                HttpHelper::getParam('prenom'),
                HttpHelper::getParam('adresse'),
                (int)HttpHelper::getParam('codePostal'),
                HttpHelper::getParam('ville'),
                (int)HttpHelper::getParam('numTel'),
                HttpHelper::getParam('email'),
                HttpHelper::getParam('activite'),
                HttpHelper::getParam('dateDebutActivite')
            );
            $userID = $this->adminservice->getUserID($pdo,$actualLogin);
            $this->adminservice->updateUser($pdo,$userID['id'],$numRPPS,$password);
            $view = $this->goFicheMedecin($pdo);
        } catch (PDOException $e) {
            $view = $this->goEditMedecin($pdo,"updateMedecin");
            if ($e->getCode() == "23000") {
                $view->setVar("numRPPSError","Ce numéro RPPS est déjà utilisé ! ");
            }
            if ($e->getCode() == "HY000") {
                $view->setVar("emailError","L'adresse mail n'est pas valide ! ");
            }
            if ($e->getCode() == "1") {
                $view->setVar("numRPPSError",$e->getMessage());
            }
            if ($e->getCode() == "2") {
                $view->setVar("dateError",$e->getMessage());
            }
        }



        return $view;

    }


    public function addMedecin($pdo) {
        try {
            $numRPPS = HttpHelper::getParam('numRPPS');
            $this->adminservice->createMedecin($pdo,$numRPPS,
                HttpHelper::getParam('nom'),
                HttpHelper::getParam('prenom'),
                HttpHelper::getParam('adresse'),
                HttpHelper::getParam('codePostal'),
                HttpHelper::getParam('ville'),
                HttpHelper::getParam('numTel'),
                HttpHelper::getParam('email'),
                HttpHelper::getParam('activite'),
                HttpHelper::getParam('dateDebutActivite')
            );
            $this->adminservice->addUser($pdo,$numRPPS,HttpHelper::getParam('password'));
            $view = $this->goFicheMedecin($pdo); 
        } catch (\PDOException $e) {
            $view = $this->goEditMedecin($pdo,"addMedecin");
            if ($e->getCode() == "23000") {
                $view->setVar("numRPPSError","Ce numéro RPPS est déjà utilisé ! ");
            }
            if ($e->getCode() == "1") {
                $view->setVar("numRPPSError",$e->getMessage());
            }
            if ($e->getCode() == "HY000") {
                $view->setVar("emailError","L'adresse mail n'est pas valide ! ");
            }
            if ($e->getCode() == "2") {
                $view->setVar("dateError",$e->getMessage());
            }
        }
        return $view;

    }

}
