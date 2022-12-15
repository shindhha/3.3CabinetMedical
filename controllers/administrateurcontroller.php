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
use services\LoginService;
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
  private $files = [["CIS_bdpm.txt","procBDPM",12],
                    ["CIS_CIP_bdpm.txt","procCIP",13],
                    ["CIS_COMPO_bdpm.txt","procCOMPO",8],
                    ["CIS_HAS_SMR_bdpm.txt","procSMR",6],
                    ["CIS_HAS_ASMR_bdpm.txt","procASMR",6],
                    ["CIS_HAS_LiensPageCT_bdpm.txt","procCT",2],
                    ["CIS_GENER_bdpm.txt","procGENER",5],
                    ["CIS_CPD_bdpm.txt","procCPD",2],
                    ["CIS_InfoImportantes.txt","procINFO",4]];
  public function __construct()
  {
      $this->importservice = ImportService::getDefaultImportService();
  }
    public function index($pdo) {      

      $view = new View("Sae3.3CabinetMedical/views/administrateur");
      
      return $view;
    }

    public function import($pdo)
    {
      foreach ($this->files as $file) {
        $fileName = $file[0];
        $sqlFunction = $file[1];
        $nbParam = $file[2];
        $this->importservice->download($fileName);
        $stmt = $this->importservice->constSQL($pdo,$nbParam,$sqlFunction);
        $this->importservice->imports($stmt,$fileName);
      }

      $view = new View("Sae3.3CabinetMedical/views/administrateur");

      return $view;
    }

    public function d($pdo)
    {
      $view = new View("Sae3.3CabinetMedical/views/administrateur");
      
      $stmt = $this->importservice->constSQL($pdo,12,"importCIP");

      
      $this->importservice->download("CIS_CIP_bdpm.txt");
      $test = $this->importservice->imports($stmt,"CIS_CIP_bdpm.txt");
      

      
       
      $view->setVar("test",$test);
      return $view;
    }

}
