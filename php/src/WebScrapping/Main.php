<?php

namespace Chuva\Php\WebScrapping;

// ADICIONA AS BIBLIOTECAS AO CÓDIGO.
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

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
    // DEFINE DIRETÓRIO E NOME DO ARQUIVO A SER CRIADO.
    $filePath = __DIR__ . '/model.xlsx';

    // CRIA UM OBJETO PARA ESCREVER PLANILHA XLSX E ABRE O ARQUIVO.
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile($filePath);

    // DEFINE ESTILOS DA PRIMEIRA LINHA E DOS DADOS.
    $firstRowStyle = (new StyleBuilder())
      ->setFontName('Arial')
      ->setFontSize(11)
      ->setFontBold()
      ->build();

    $style = (new StyleBuilder())
      ->setFontName('Arial')
      ->setFontSize(11)
      ->build();

    // DEFINE TEXTO CABEÇALHO (1 LINHA)
    $firstRow = ["ID", "Title", "Type"];
    // COMO A MAIOR QUANTIDADE DE AUTORES É 16, ESCREVE 16 VEZES.
    for ($x = 1; $x <= 16; $x++) {
      array_push($firstRow, "Author " . $x, "Author " . $x . " Institution");
    }
    // ADICIONA PRIMEIRA LINHA A PLANILHA.
    $writer->addRow(
      WriterEntityFactory::createRowFromArray($firstRow, $firstRowStyle)
    );

    // PARA CADA DADO RETORNADO DA FUNÇÃO 'scrap()'
    foreach ($data[0] as $paper) {
      // PREPARA UM ARRAY PARA SER INSERIDO NAS LINHAS SEGUINTES.
      $newRow = [
        $paper->id,
        $paper->title,
        $paper->type,
      ];
      // PARA CADA AUTOR DO PAPER ADICIONA SEU NOME E INSTITUIÇÃO.
      foreach ($paper->authors as $author) {
        array_push($newRow, $author->name);
        array_push($newRow, $author->institution);
      }

      // ADICIONA A LINHA NA PLANILHA.
      $writer->addRow(
        WriterEntityFactory::createRowFromArray($newRow, $style)
      );
    }

    // ENCERRA CONEXÃO COM O ARQUIVO.
    $writer->close();

  }

}
