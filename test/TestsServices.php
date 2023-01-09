<?php


spl_autoload_extensions(".php");
spl_autoload_register();

use PHPUnit\Framework\TestCase;


use services\ImportService;



class TestsServices extends TestCase {

	private $importservice;
	private $CorrectLines;
	private $exceptedLinesOutput;
	private $CorrectDates;
	private $exceptedDatesOutput;
	private $exceptedSQLRequest;
	private $files;
	private $pdo;

	protected function setUp(): void
	{
		$this->importservice = ImportService::getDefaultImportService();
		$this->CorrectLines = [
        // CIS_bdpm.txt
			"61266250	A 313 200 000 UI POUR CENT, pommade	pommade	cutanée	Autorisation active	Procédure nationale	Commercialisée	12/03/1998			 PHARMA DEVELOPPEMENT	Non",

		// CIS_CIP_bdpm.txt
			"60002283	4949729	plaquette(s) PVC PVDC aluminium de 30 comprimé(s)	Présentation active	Déclaration de commercialisation	16/03/2011	3400949497294	oui	100%	30,36	31,38	1,02	",
		// CIS_COMPO_bdpm.txt
			"60002283	comprimé	42215	ANASTROZOLE	1,00 mg	un comprimé	SA	1	",
		// CIS_HAS_SMR.txt
			"60433344	CT-20051	Inscription (CT)	20221123	Important	le service médical rendu par les spécialités TADALAFIL TEVA 2,5 mg, 5 mg, 10 mg, 20 mg, en comprimés pelliculés est important uniquement chez les hommes adultes ayant des troubles de l’érection liés à l’une des pathologies suivantes :  <br>- Paraplégie et tétraplégie quelle qu’en soit l’origine .<br>- Traumatismes du bassin compliqués de troubles urinaires .<br>- Séquelles de la chirurgie (anévrisme de l’aorte . prostatectomie radicale, cystectomie totale et exérèse colorectale) ou de la radiothérapie abdominopelvienne .<br>- Séquelles de priapisme .<br>- Neuropathie diabétique .<br>- Sclérose en plaques .<br>- Et chez ceux ayant un trouble de l’érection dû à un traitement au long cours par un antipsychotique. ; G",
		// CIS_HAS_ASMR.txt
			"60433344	CT-20051	Inscription (CT)	20221123	V	Les spécialités TADALAFIL TEVA (tadalafil) 2,5 mg, 5 mg, 10 mg et 20 mg, comprimés pelliculés, n’apportent pas d’amélioration du service médical rendu (ASMR V) par rapport aux autres spécialités à base de tadalafil déjà disponibles. ; G",
		// HAS_LiensPageCt_bdpm.txt
			"CT-19869	https://www.has-sante.fr/jcms/p_3390335",
		// CIS_GENER_bdpm.txt
			"1	CIMETIDINE 200 mg - TAGAMET 200 mg, comprimé pelliculé	65383183	0	1	",
		// CIS_CPD_bdpm.txt
			"60355340	réservé à l'usage professionnel DENTAIRE",
		// CIS_InfoImportantes.txt
			"60000777	2018-10-29	2023-10-29	<a target='_blank'  title=\"Lien direct vers l'information importante sur le site de l'ANSM - Nouvelle fen&ecirc;tre\" href='https://www.ansm.sante.fr/S-informer/Points-d-information-Points-d-information/Antipsychotiques-rappel-des-mesures-de-suivi-cardio-metabolique-Point-d-Information'>Antipsychotiques : rappel des mesures de suivi cardio-métabolique - Point d'Information</a>"
		];
		$this->exceptedLinesOutput = [
			["61266250","A 313 200 000 UI POUR CENT, pommade","pommade","cutanée","Autorisation active","Procédure nationale","Commercialisée","19980312","","","PHARMA DEVELOPPEMENT","Non"],
			["60002283","4949729","plaquette(s) PVC PVDC aluminium de 30 comprimé(s)","Présentation active","Déclaration de commercialisation","20110316","3400949497294","oui","100%","30,36","31,38","1,02",""],
			["60002283","comprimé","42215","ANASTROZOLE","1,00 mg","un comprimé","SA","1"],
			["60433344","CT-20051","Inscription (CT)","20221123","Important","le service médical rendu par les spécialités TADALAFIL TEVA 2,5 mg, 5 mg, 10 mg, 20 mg, en comprimés pelliculés est important uniquement chez les hommes adultes ayant des troubles de l’érection liés à l’une des pathologies suivantes :  <br>- Paraplégie et tétraplégie quelle qu’en soit l’origine .<br>- Traumatismes du bassin compliqués de troubles urinaires .<br>- Séquelles de la chirurgie (anévrisme de l’aorte . prostatectomie radicale, cystectomie totale et exérèse colorectale) ou de la radiothérapie abdominopelvienne .<br>- Séquelles de priapisme .<br>- Neuropathie diabétique .<br>- Sclérose en plaques .<br>- Et chez ceux ayant un trouble de l’érection dû à un traitement au long cours par un antipsychotique. ; G"],
			["60433344","CT-20051","Inscription (CT)","20221123","V","Les spécialités TADALAFIL TEVA (tadalafil) 2,5 mg, 5 mg, 10 mg et 20 mg, comprimés pelliculés, n’apportent pas d’amélioration du service médical rendu (ASMR V) par rapport aux autres spécialités à base de tadalafil déjà disponibles. ; G"],
			["CT-19869","https://www.has-sante.fr/jcms/p_3390335"],
			["1","CIMETIDINE 200 mg - TAGAMET 200 mg, comprimé pelliculé","65383183","0","1"],
			["60355340","réservé à l'usage professionnel DENTAIRE"],
			["60000777","2018-10-29","2023-10-29","<a target='_blank'  title=\"Lien direct vers l'information importante sur le site de l'ANSM - Nouvelle fen&ecirc;tre\" href='https://www.ansm.sante.fr/S-informer/Points-d-information-Points-d-information/Antipsychotiques-rappel-des-mesures-de-suivi-cardio-metabolique-Point-d-Information'>Antipsychotiques : rappel des mesures de suivi cardio-métabolique - Point d'Information</a>"]
		];
		$this->CorrectDates = [
			"12/03/1998",
			"312/03/1998",
			"12/03/19985",
			"312/03/19985",
			"A12/03/1998B"
		];
		$this->exceptedDatesOutput = [
			"19980312",
			"319980312",
			"199803125",
			"3199803125",
			"A19980312B"
		];

		$this->files = [["CIS_bdpm.txt","procBDPM",12,false],
					["CIS_CIP_bdpm.txt","procCIP",13,false],
					["CIS_COMPO_bdpm.txt","procCOMPO",8,true],
					["CIS_HAS_SMR_bdpm.txt","procSMR",6,false],
					["CIS_HAS_ASMR_bdpm.txt","procASMR",6,false],
					["CIS_HAS_LiensPageCT_bdpm.txt","procCT",2,false],
					["CIS_GENER_bdpm.txt","procGENER",5,true],
					["CIS_CPD_bdpm.txt","procCPD",2,false],
					["CIS_InfoImportantes.txt","procINFO",4,false]];

		$this->pdo = new PDO("mysql:host=sql.alphaline.ml;port=3306;dbname=SAE_TESTS;charset=utf8", "guillaume", "guillaume");

		$this->exceptedSQLRequest = [
			"CALL procBDPM (?,?,?,?,?,?,?,?,?,?,?,?)",
			"CALL procCIP (?,?,?,?,?,?,?,?,?,?,?,?,?)",
			"CALL procCOMPO (?,?,?,?,?,?,?,?)",
			"CALL procSMR (?,?,?,?,?,?)",
			"CALL procASMR (?,?,?,?,?,?)",
			"CALL procCT (?,?)",
			"CALL procGENER (?,?,?,?,?)",
			"CALL procCPD (?,?)",
			"CALL procINFO (?,?,?,?)"

		];

	}


	/**
	 * Le jeu de test est composé de la première ligne de chaque
	 * fichier , les ' " ' on volontairement été remplacer par des ' \" '
	 */
	

	public function testFormatDates()
	{	
		
		for ($i=0; $i < sizeof($this->CorrectDates); $i++) { 
			$this->assertSame($this->importservice->formatDate($this->CorrectDates[$i]), $this->exceptedDatesOutput[$i]);
		}
		
	}

	public function testPrepareLine()
	{
		for ($i=0; $i < sizeof($this->CorrectLines); $i++) { 
			$trim = $this->files[$i][3];
			$d = $this->importservice->FormatLine($this->CorrectLines[$i],$trim);
			$this->assertSame($d,$this->exceptedLinesOutput[$i]);
		}
		
	}

	public function testConstructSQL()
	{
		for ($i=0; $i < sizeof($this->files); $i++) { 
			$functionName = $this->files[$i][1];
			$nbParam = $this->files[$i][2];
			$function = $this->importservice->constructSQL($this->pdo,$nbParam,$functionName);
			$this->assertEquals($function,$this->pdo->prepare($this->exceptedSQLRequest[$i]));
		}
	}


	public function testExportToBD()
	{
		$nbInsert = 0;
		for ($i=0; $i < sizeof($this->files); $i++) { 
			$stmt = $this->pdo->prepare($this->exceptedSQLRequest[$i]);

			try {
				$stmt->execute($this->exceptedLinesOutput[$i]);
				$nbInsert++;
			} catch (PDOException $e) {

				echo $e->getMessage();
				echo "Sur le test n°" . $i;
			}
		}
		$this->assertEquals($nbInsert, sizeof($this->exceptedSQLRequest));
	}
	
}