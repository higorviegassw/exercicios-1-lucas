<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {

    $xpath = new \DomXPath($dom);

    // RETORNA TODOS AS TAGS "a" COM CLASSE 'paper-card'
    $paperCards = $xpath->query("//a[contains(@class,'paper-card')]");

    $objPapersArray = [];

    // PARA CADA 'a.paper-card'
    foreach($paperCards as $paper) {
      
      // RETORNA CADA CAMPO ESPECÍFICO DE CADA PAPER.
      $id = $xpath->query(".//following::div[contains(@class,'volume-info')]", $paper);
      $title = $xpath->query(".//h4[contains(@class,'paper-title')]", $paper);
      $type = $xpath->query(".//following::div[contains(@class,'tags')]", $paper);


      // RETORNA A DIV DA LISTA DE AUTORES 'div.authors' DO PAPER.
      $authorsDiv = $xpath->query(".//following::div[contains(@class,'authors')]", $paper);

      // PARA CADA 'span' EM 'div.authors'
      $authors = $xpath->query(".//span", $authorsDiv[0]);

      // CRIA ARRAY DE OBJETOS DE AUTORES.
      $objAuthorsArray = array();
      foreach($authors as $author) {
        // CRIA ARRAY INDIVIDUAL PARA CADA AUTOR.
        // CONDICIONAL NECESSÁRIO POIS NO ID 137475 APÓS RAFAEL ALVES DE ANDRADE EXISTE UM CAMPO VAZIO.
        if($author->nodeValue != "") {
          // RETORNA O VALOR ABSOLUTO DO NÓ.
          $authorName = str_replace(";", "", $author->nodeValue);
          // RETORNA O VALOR DO ATRIBUTO 'title'
          $authorInstitution = $author->attributes["title"]->nodeValue;
          // INSERE NO ARRAY INDIVIDUAL NOME E INSTITUIÇÃO DO AUTOR.
          array_push($objAuthorsArray, new Person($authorName, $authorInstitution));
        }
      }

      $objPaper = new Paper(
        $id[0]->nodeValue, 
        $title[0]->nodeValue, 
        $type[0]->nodeValue,
        $objAuthorsArray
      );

      array_push($objPapersArray, $objPaper);
    }

    return [
      $objPapersArray,
    ];
  }

}
