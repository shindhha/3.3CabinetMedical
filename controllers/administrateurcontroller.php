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
  private $files = [["CIS_bdpm.txt","importBDPM",12],
                    ["CIS_CIP_bdpm.txt","importCIP",11],
                    ["CIS_COMPO_bdpm.txt","importCOMPO",8],
                    ["CIS_HAS_SMR_bdpm.txt","importSMR",6],
                    ["CIS_HAS_ASMR_bdpm.txt","importASMR",6],
                    ["CIS_HAS_LiensPageCT_bdpm.txt","importCT",2],
                    ["CIS_GENER_bdpm.txt","importGENER",5],
                    ["CIS_CPD_bdpm.txt","importCPD",2],
                    ["CIS_InfoImportantes.txt","importINFO",4]]
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
        $this->importservice->imports($pdo,$nbParam,$fileName,$sqlFunction);
      }

      $view = new View("Sae3.3CabinetMedical/views/administrateur");

      return $view;
    }

}
