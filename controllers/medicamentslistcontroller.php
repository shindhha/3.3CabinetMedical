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

class medicamentslistController
{
	private $usersservices;

	public function __construct()
	{
		$this->usersservices = UsersServices::getDefaultUsersService();
	}
	public function index($pdo) {
		$view = new View("Sae3.3CabinetMedical/views/medicamentsList");

        $pformePharma = HttpHelper::getParam("pformePharma") !== null ? HttpHelper::getParam("pformePharma") : "%" ;
        $pVoieAdmi = HttpHelper::getParam("pVoieAdmi") !== null ? HttpHelper::getParam("pVoieAdmi") : "%" ;
        $pTauxRem = HttpHelper::getParam("pTauxRem") !== null ? HttpHelper::getParam("pTauxRem") : "";
        $pPrixMin = (int) HttpHelper::getParam("pPrixMin");
        $pPrixMax = (int) HttpHelper::getParam("pPrixMax") == 0 ? 10000 : (int) HttpHelper::getParam("pPrixMax");
        $pEtat =  HttpHelper::getParam("pEtat") !== null ? (int) HttpHelper::getParam("pEtat") : -1;
        $pSurveillance = HttpHelper::getParam("pSurveillance") !== null ? (int) HttpHelper::getParam("pSurveillance") : -1;

        $d = [$pformePharma,$pVoieAdmi,$pTauxRem,$pPrixMin,$pPrixMax,$pEtat,$pSurveillance];
        
        $drugs = $this->usersservices->getListMedic($pdo,$pformePharma,$pVoieAdmi,$pEtat,$pTauxRem,$pPrixMin,$pPrixMax,$pSurveillance);
        $formePharmas = $this->usersservices->getparams($pdo,"formePharma","FormePharma");
        $voieAdministration = $this->usersservices->getparams($pdo,"labelVoieAdministration","ID_Label_VoieAdministration");
        $tauxRemboursements = $this->usersservices->getparams($pdo,"tauxRemboursement","TauxRemboursement");
        $view->setVar("pformePharma",$pformePharma);
        $view->setVar("pVoieAdmi",$pVoieAdmi);
        $view->setVar("pTauxRem",$pTauxRem);
        $view->setVar("pPrixMin",$pPrixMin);
        $view->setVar("pPrixMax",$pPrixMax);
        $view->setVar("pEtat",$pEtat);
        $view->setVar("pSurveillance",$pSurveillance);
        $view->setVar("d",$d);
        $view->setVar("tauxRemboursements",$tauxRemboursements);
        $view->setVar("voieAd",$voieAdministration);
		$view->setVar("formePharmas",$formePharmas);
		$view->setVar("drugs",$drugs);

		return $view;


	}

}
