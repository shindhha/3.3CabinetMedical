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

class ConnectionController
{
  private $usersservices;

  public function __construct()
  {
      $this->usersservices = UsersServices::getDefaultUsersService();
  }
    public function index($pdo) {
      $username = HttpHelper::getParam('login');
      $password = HttpHelper::getParam('password');
      $searchStmt = $this->usersservices->findIfAdminExists($pdo,$username,$password);
      $view = new View("Sae3.3CabinetMedical/views/connection");

      if ($searchStmt) {
        if ($username == "admin") {
          header("Location: index.php?controller=administrateur");
        } else {
          header("Location: index.php?controller=medicamentslist");
          $_SESSION['id'] = $username;
        }
        
      }

        return $view;
    }

}
