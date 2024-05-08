<?php

namespace Chuva\Php\WebScrapping;

//  ADICIONA AS BIBLIOTECAS AO CÓDIGO
use Box\Spout\Writer\WriterEntityFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    // Write your logic to save the output file bellow.
    print_r($data);

    print("\n\n\n\n\n\n\n\n");

    //$writer = WriterEntityFactory::createWriterFromFile(__DIR__ . '/../../assets/test.xlsx');
    //$reader = ReaderEntityFactory::createReaderFromFile(__DIR__ . '/../../assets/test.xlsx');

    print_r($reader);
  }

}
/**
 * LINHA 1
 * ID
 * Title
 * Type
 * Author X
 * Author X Institution
 * X 1~9
 * 
 * LINHA 2
 * [DADOS]
 */

 /*
  LINKS
  https://products.fileformat.com/pt/spreadsheet/php/spout/ 
  
  
  
  */