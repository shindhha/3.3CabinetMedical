<?php


namespace services;

use PDOException;
use DateTime;

/**
 *
 */
class ImportService 
{ 

	private static $defaultImportService;
	private static $path = "res/";

	public static function getDefaultImportService() {
		if (ImportService::$defaultImportService == null) {
			ImportService::$defaultImportService = new ImportService();
		}
		return ImportService::$defaultImportService;
	}

	public function formatDate ($text) {

		preg_match_all("#[0-9]{2}\/[0-9]{2}\/[0-9]{4}#",$text,$dates);
		foreach ($dates[0] as $date) {
			$format = DateTime::createFromFormat('d/m/Y',$date);
			$dateAfter = $format->format("Ymd");
			$text = str_replace($date, $dateAfter, $text);
		}
		return $text;
	}


	public function formatDecimal($text)
	{
  		preg_match_all("#,[0-9]{2}	#",$text,$decimals);
  		foreach ($decimals[0] as $decimal) {
  			$newDecimal = str_replace(",",".",$decimal);
			$text = str_replace($decimal, $newDecimal, $text);
		}
		preg_match_all("#,[0-9]{3}#",$text,$decimals);
		foreach ($decimals[0] as $decimal) {
  			$newDecimal = str_replace(",","",$decimal);
			$text = str_replace($decimal, $newDecimal, $text);
		}
		return $text;
	}

	public function download ($name) {
		$destination = ImportService::$path.$name;
		$source = "https://base-donnees-publique.medicaments.gouv.fr/telechargement.php?fichier=".$name;
		$ch = curl_init($source);
		$fp = fopen($destination, "w");
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		if(curl_error($ch)) {
			fwrite($fp, curl_error($ch));
		}
		curl_close($ch);
		fclose($fp);
	}


	public function constructSQL($pdo,$nbParam,$sqlFunction,$import) {
		$param = "";
		$sql = "";

		for ($i=0; $i < $nbParam ; $i++) { 
			$param = $param . "?";
			if ($i < $nbParam - 1) {
				$param = $param . ",";
			}
		}

		if ($import) {
			$sql = "SELECT " . "import" . $sqlFunction . " (" . $param . ")";
		} else {
			$sql = "SELECT " . "update" . $sqlFunction . " (" . $param . ")";
		}
		$stmt = $pdo->prepare($sql);
		return $stmt;
	}

	public function FormatLine($line,$trimLine) {
		$line = iconv(mb_detect_encoding($line, mb_detect_order(), true), "UTF-8", $line);
		$line = $this->formatDate($line);
		$line = $this->formatDecimal($line);
		if ($trimLine) $line = trim($line);
		$args = explode("\t",$line);
		for ($i = 0 ; $i < count($args) ; $i++) {
			$args[$i] = trim($args[$i]);
		}
		return $args;
	}

	public function exportToBD ($pdo,$importStmt,$updateStmt,$param) {
		$trimLine = $param[3];
		$iCis = $param[4];
		$table = $param[5];
		$prefixe = $param[6];
		$fileName = $param[0];
		$compteur = 0;
		$table = $prefixe . $table;
		$file = fopen(ImportService::$path.$fileName, "r");
		$content = fread($file, filesize(ImportService::$path.$fileName));
		$lines = explode("\n", $content);
		foreach ($lines as $line) {
			$args = $this->FormatLine($line,$trimLine);
			$calledFunction = "";
			if ($fileName == "HAS_LiensPageCT_bdpm.txt"){
				$d = $this->CTExists($pdo,$args[$iCis],$table);
			}else{
				$d = $this->CISExists($pdo,$args[$iCis],$table);
			}
			try {
				$pdo->beginTransaction();

				

				if ($d) {
					$calledFunction = "update" . $fileName;
					$updateStmt->execute($args);
					
					
				} else {
					$calledFunction = "import" . $fileName;
					$importStmt->execute($args);
					
				}

				$pdo->commit();
				$compteur++;
				
			} catch (PDOException $e) {
				$pdo->rollback();
				$this->insertError($pdo,$calledFunction . " Line " . $args[$iCis]  ,$e->getCode(),$e->getMessage());
				
				
				


				
			}
			
		}
	}

	public function CISExists($pdo,$numCIS,$table)
	{
		$stmt = $pdo->prepare("SELECT * FROM " . $table . " WHERE codeCis = :codeCIS");
		$stmt->bindParam("codeCIS",$numCIS);
		$stmt->execute();
		return $stmt->rowcount() > 0;
	}

	public function CTExists($pdo,$numCT,$table)
	{
		$stmt = $pdo->prepare("SELECT * FROM " . $table . " WHERE codeHAS = :codeHAS");
		$stmt->bindParam("codeHAS",$numCT);
		$stmt->execute();
		return $stmt->rowcount() > 0;
	}

	public function insertError($pdo,$calledFunction,$errCode ,$errMessage)
	{
		$stmt = $pdo->prepare("INSERT INTO ErreursImportation (nomProcedure, messageErreur) VALUES (:calledFunction, CONCAT(:errCode, ' - ', :errMessage))");

		$stmt->execute(array('calledFunction' => $calledFunction,
							 'errCode' =>  $errCode,
							 'errMessage' =>  $errMessage));
	}
}