<?php


namespace services;

use PDOException;

/**
 *
 */
class ImportService
{
  
  private static $defaultImportService;
  private static $path = "res";

  public static function getDefaultImportService()
  {
    if (ImportService::$defaultImportService == null) {
      ImportService::$defaultImportService = new ImportService();
    }
    return ImportService::$defaultImportService;
  }

  public static function formatDate ($text) {
    date_default_timezone_set("UTC");
    preg_match_all("#[0-9]{2}\/[0-9]{2}\/[0-9]{4}#",$text,$dates);
    foreach ($dates as $date) {
      $dateAfter = date('Y-m-d H:i:s' , strtotime($date));
      preg_replace($date, $dateAfter, $text);
    }
    return $date;

  }


  public static function imports ($pdo,$nbParam,$file,$sqlFunction) {

    $content = fread($file, filesize($filePath));
    $lines = explode("\n", $content);

    $sql = "CALL " . $sqlFunction . " (";

    for ($i=0; $i < $nbParam ; $i++) { 
      $sql = $sql . "? ,";
    }
    $sql = $sql . ")";
    $stmt = $pdo->prepare($sql);

    foreach ($lines as $line) {
      $line = formatDate($line);
      $args = explode("\t", $line);
      $stmt->execute($args);
    }

  }
}