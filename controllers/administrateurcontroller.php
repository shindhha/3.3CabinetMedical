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

class AdministrateurController
{
	private $importservice;
	private $files = [["CIS_bdpm.txt","BDPM",12,false,0,"BDPM"],
					  ["CIS_CIP_bdpm.txt","CIP",13,false,0,"CIP_BDPM"],
					  ["CIS_COMPO_bdpm.txt","COMPO",8,true,0,"COMPO"],
					  ["CIS_HAS_SMR_bdpm.txt","SMR",6,false],
					  ["CIS_HAS_ASMR_bdpm.txt","ASMR",6,false],
					  ["CIS_HAS_LiensPageCT_bdpm.txt","CT",2,false],
					  ["CIS_GENER_bdpm.txt","GENER",5,true,2,"GENER"],
					  ["CIS_CPD_bdpm.txt","CPD",2,false],
					  ["CIS_InfoImportantes.txt","INFO",4,false]];
	public function __construct() {
		$this->importservice = ImportService::getDefaultImportService();
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
		(int) $file = HttpHelper::getParam('file');
		$filep = $this->files[$file][0];
		$function = $this->files[$file][1];
		$nbParam = $this->files[$file][2];
		$trimLine = $this->files[$file][3];
		$iCis = $this->files[$file][4];
		$bd = $this->files[$file][5];
		
		
		try {
			
			

			$importStmt = $this->importservice->constructSQL($pdo,$nbParam,$function,true);
			$updateStmt = $this->importservice->constructSQL($pdo,$nbParam,$function,false);
			
			$test = $this->importservice->exportToBD($pdo,$importStmt,$updateStmt,$filep,$trimLine,$iCis,$bd);
			
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), $e->getCode());
			
		}

		

		return $view;
	}

}
