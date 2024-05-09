<?php

namespace Chuva\Php\WebScrapping;

//  ADICIONA AS BIBLIOTECAS AO CÓDIGO
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;

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
    // print_r($data);

    // DEFINE DIRETÓRIO E NOME DO ARQUIVO A SER CRIADO
    $filePath = __DIR__ . '/../../assets/model.xlsx';
    $tempFilePath = __DIR__ . '/../../assets/model copy.xlsx';

    // $writer = WriterEntityFactory::createWriterFromFile(__DIR__ . '/../../assets/test.xlsx');
    // $reader = ReaderEntityFactory::createReaderFromFile(__DIR__ . '/../../assets/test.xlsx');
    // $reader = ReaderEntityFactory::createXLSXReader();
    // $reader->open($filePath);

    // CRIA UM OBJETO PARA ESCREVER PLANILHA XLSX E ABRE O ARQUIVO
    $writer = WriterEntityFactory::createXLSXWriter();
    // $writer->openToFile($tempFilePath);
    $writer->openToFile($filePath);

    // DEFINE ESTILOS DA PRIMEIRA LINHA E DOS DADOS
    $firstRowStyle = (new StyleBuilder())
           ->setFontName('Arial')
           ->setFontSize(11)
           ->setFontBold()
           ->build();

    $style = (new StyleBuilder())
           ->setFontName('Arial')
           ->setFontSize(11)
           ->build();
    

    //print_r($writer);
    // $counter = 0;
    // foreach ($reader->getSheetIterator() as $sheet) {
    //   foreach ($sheet->getRowIterator() as $row) {
    //     //  IGNORA A PRIMEIRA LINHA
    //       //if ($counter++ == 0) continue;

          
    //       //  DUAS FORMAS DE FAZER: TRANSFORMAR A $row EM ARRAY
    //       $cells = $row->toArray();
    //       //  RETIRAR AS CELULAS DA LINHA
    //       //print_r($row->getCells());
    //       //print_r($cells);

    //       //  FILTRO PARA RETIRAR ENTRADAS VAZIAS
    //       print_r(array_filter($cells, fn($value) => !is_null($value) && $value !== ''));
          
    //       foreach($row->getCells() as $cell) {
    //         print_r($cell->getValue()."\n");
    //         //if($cell != "") print_r("\n".$cell);
    //       }
          
    //   }
    // }

    
    // ID	Title	Type	Author 1	Author 1 Institution	Author 2	Author 2 Institution	Author 3	Author 3 Institution	Author 4	Author 4 Institution	Author 5	Author 5 Institution	Author 6	Author 6 Institution	Author 7	Author 7 Institution	Author 8	Author 8 Institution	Author 9	Author 9 Institution

    // DEFINE TEXTO CABEÇALHO (1 LINHA)
    $firstRow = array("ID", "Title", "Type");
    // COMO A MAIOR QUANTIDADE DE AUTORES É 16, ESCREVE 16 VEZES
    for($x = 1; $x <= 16; $x++) {
      // print("Author ".$x);
      array_push($firstRow, "Author ".$x, "Author ".$x." Institution");
    }
    // ADICIONA PRIMEIRA LINHA A PLANILHA
    $writer->addRow(
      WriterEntityFactory::createRowFromArray($firstRow, $firstRowStyle)
    );

    // PARA CADA DADO RETORNADO DA FUNÇÃO 'scrap()'
    foreach($data[0] as $paper) {
      // PREPARA UM ARRAY PARA SER INSERIDO NAS LINHAS SEGUINTES
      //print_r("\n");
      $newRow = array(
        $paper->id,
        $paper->title,
        $paper->type
      );
      // PARA CADA AUTOR DO PAPER ADICIONA SEU NOME E INSTITUIÇÃO
      foreach($paper->authors as $author) {
        array_push($newRow, $author->name);
        array_push($newRow, $author->institution);
      }


      // ADICIONA A LINHA NA PLANILHA
      //print_r($newRow);
      $writer->addRow(
        WriterEntityFactory::createRowFromArray($newRow, $style)
      );  
    }

    
    
    // print_r("\n");
    // print_r($newRow);


    // At this point, the new spreadsheet contains the same data as the existing one.
    // So let's add the new data:
    // $writer->addRow(
    //   WriterEntityFactory::createRowFromArray(['2015-12-25', 'Christmas gift', 29, 'USD'], $style)
    // );
    
    //$reader->close();
    // ENCERRA CONEXÃO COM O ARQUIVO
    $writer->close();

    
    

    // unlink($filePath);
    // copy($tempFilePath, $filePath);
    // unlink($tempFilePath);
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
  https://stackoverflow.com/questions/3654295/remove-empty-array-elements
  https://opensource.box.com/spout/docs/
  
  
  */

  // TESTE