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

class ConnectionController
{
  private $LoginService;

  public function __construct()
  {
      $this->LoginService = LoginService::getDefaultUsersService();
  }
    public function index($pdo) {
      $username = HttpHelper::getParam('login');
      $password = htmlspecialchars(HttpHelper::getParam('password'));
      $searchStmt = $this->LoginService->findIfAdminExists($pdo,$username,$password);
      $view = new View("Sae3.3CabinetMedical/views/connection");

      if ($searchStmt) {
        if ($username == "admin") {
          $view = new View("Sae3.3CabinetMedical/views/administrateur");
        } else {
          $view = new View("Sae3.3CabinetMedical/views/medicamentsList");
        }
        
      }

        return $view;
    }

}
