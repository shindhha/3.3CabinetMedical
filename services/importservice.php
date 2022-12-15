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

  public static function getDefaultImportService()
  {
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


  public function constSQL($pdo,$nbParam,$sqlFunction)
  {
    $sql = "SELECT " . $sqlFunction . " (";

      for ($i=0; $i < $nbParam ; $i++) { 
        $sql = $sql . "?";
        if ($i < $nbParam - 1) {
          $sql = $sql . " ,";
        }
      }
      $sql = $sql . ")";
      $stmt = $pdo->prepare($sql);
      return $stmt;
    }

    public function imports ($stmt,$fileName) {
      $file = fopen(ImportService::$path.$fileName, "r");
      $content = fread($file, filesize(ImportService::$path.$fileName));
      $lines = explode("\n", $content);
      foreach ($lines as $line) {
        $line = ImportService::formatDate($line);
        $line = utf8_encode($line);

        $args = explode("\t", $line);
        for ($i = 0 ; $i < count($args) ; $i++) {
          $args[$i] = trim($args[$i]);
        }
        return [$stmt,$args];
        try {
          
          $stmt->execute($args);
        } catch (PDOException $e) {
          echo $e->getMessage();
        }
        
      }
    }
  }