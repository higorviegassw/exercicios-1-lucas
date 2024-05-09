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

    //  RETORNA TODOS AS TAGS "a" COM CLASSE 'paper-card'
    $paperCards = $xpath->query("//a[contains(@class,'paper-card')]");

    $objPapersArray = array();

    //  PARA CADA 'a.paper-card'
    foreach($paperCards as $paper) {
      
      //  RETORNA CADA CAMPO ESPECÍFICO DE CADA PAPER
      $id = $xpath->query(".//following::div[contains(@class,'volume-info')]", $paper);
      $title = $xpath->query(".//h4[contains(@class,'paper-title')]", $paper);
      $type = $xpath->query(".//following::div[contains(@class,'tags')]", $paper);


      //  RETORNA A DIV DA LISTA DE AUTORES 'div.authors' DO PAPER
      $authorsDiv = $xpath->query(".//following::div[contains(@class,'authors')]", $paper);

      // PARA CADA 'span' EM 'div.authors'
      $authors = $xpath->query(".//span", $authorsDiv[0]);

      //  CRIA ARRAY DE OBJETOS DE AUTORES
      $objAuthorsArray = array();
      foreach($authors as $author) {
        //  CRIA ARRAY INDIVIDUAL PARA CADA AUTOR
        //  CONDICIONAL NECESSÁRIO POIS NO ID 137475 APÓS RAFAEL ALVES DE ANDRADE EXISTE UM CAMPO VAZIO
        if($author->nodeValue != "") {
          //  RETORNA O VALOR ABSOLUTO DO NÓ
          $authorName = str_replace(";", "", $author->nodeValue);
          //  RETORNA O VALOR DO ATRIBUTO 'title'
          $authorInstitution = $author->attributes["title"]->nodeValue;
          //  INSERE NO ARRAY INDIVIDUAL NOME E INSTITUIÇÃO DO AUTOR
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


/* ESTRUTURA DO ARRAY
[
    [
        "id" => "123",
        "title" => "TITULO",
        "type" => "TIPO",
        "authors" => ["name" => "NOME DO AUTOR", "institution" => "INSTITUIÇÃO"]
    ]
]
*/

/*
PHP links
https://scrapfly.io/blog/how-to-select-elements-by-class-in-xpath/
https://www.php.net/manual/en/class.domxpath.php
https://www.php.net/manual/en/domxpath.construct.php
https://stackoverflow.com/questions/48085772/php-domxpath-loop-through-search-and-find-child-div-value
https://www.php.net/manual/en/function.count.php
https://www.php.net/manual/pt_BR/control-structures.for.php
https://www.php.net/manual/en/function.str-replace.php
https://www.php.net/manual/en/function.array-keys.php
https://www.php.net/manual/en/function.array-push.php
https://stackoverflow.com/questions/676677/how-to-add-elements-to-an-empty-array-in-php
https://stackoverflow.com/questions/1544176/accessing-arrays-inside-arrays-in-php
*/