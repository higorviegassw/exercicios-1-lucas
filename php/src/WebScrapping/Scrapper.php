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


    //print("\n\n\n\n\n\n\n\n");


    //print_r($dom);
    $xpath = new \DomXPath($dom);
    // print_r($xpath);
    // $paperCards = $xpath->query("//a[@class='paper-card p-lg bd-gradient-left']");

    //  RETORNA TODOS AS TAGS "a" COM CLASSE 'paper-card'
    $paperCards = $xpath->query("//a[contains(@class,'paper-card')]");


    // $titles = $xpath->query("//h4[contains(@class,'paper-title')]");

    // for($x=0 ; $x < count($paperCards) ; $x++) {
    //   $id = $xpath->query(".//following::div[contains(@class,'volume-info')]", $paperCards[$x]);
    //   $title = $xpath->query(".//following::h4[contains(@class,'paper-title')]", $paperCards[$x]);
    //   $type = $xpath->query(".//following::div[contains(@class,'tags')]", $paperCards[$x]);



    //   print_r(
    //     $id[0]->nodeValue." | "
    //     .$title[0]->nodeValue." | "
    //     .$type[0]->nodeValue
    //     ."\n\n"
    //   );
    //   // print_r($paperCards[$x]);
    // }

    // $firstPaper = $paperCards->item(0);

    // $authorsDiv = $xpath->query(".//following::div[contains(@class,'authors')]", $firstPaper);

    // $authors = $xpath->query(".//span", $authorsDiv[0]);
    
    // print_r(str_replace(";", "", $authors[0]->nodeValue));
    // print_r($authors[0]->attributes["title"]->nodeValue);

    $papersArray = array();

    $objPapersArray = array();

    //  PARA CADA 'a.paper-card'
    foreach($paperCards as $paper) {
      $paperArray = array();
      
      //  RETORNA CADA CAMPO ESPECÍFICO DE CADA PAPER
      $id = $xpath->query(".//following::div[contains(@class,'volume-info')]", $paper);
      $title = $xpath->query(".//h4[contains(@class,'paper-title')]", $paper);
      $type = $xpath->query(".//following::div[contains(@class,'tags')]", $paper);

      $paperArray['id'] = $id[0]->nodeValue;
      $paperArray['title'] = $title[0]->nodeValue;
      $paperArray['type'] = $type[0]->nodeValue;


      //  RETORNA A DIV DA LISTA DE AUTORES 'div.authors' DO PAPER
      $authorsDiv = $xpath->query(".//following::div[contains(@class,'authors')]", $paper);

      // PARA CADA 'span' EM 'div.authors'
      $authors = $xpath->query(".//span", $authorsDiv[0]);
      
      //  CRIA ARRAY DE AUTORES
      $authorsArray = array();
      //  CRIA ARRAY DE OBJETOS DE AUTORES
      $objAuthorsArray = array();
      foreach($authors as $author) {
        //  CRIA ARRAY INDIVIDUAL PARA CADA AUTOR
        $authorArray = array();
        //  CONDICIONAL NECESSÁRIO POIS NO ID 137475 APÓS RAFAEL ALVES DE ANDRADE EXISTE UM CAMPO VAZIO
        if($author->nodeValue != "") {
          //  RETORNA O VALOR ABSOLUTO DO NÓ
          $authorName = str_replace(";", "", $author->nodeValue);
          //  RETORNA O VALOR DO ATRIBUTO 'title'
          $authorInstitution = $author->attributes["title"]->nodeValue;
          $authorArray['name'] = $authorName;
          $authorArray['institution'] = $authorInstitution;
          //print_r($authorName." / ".$authorInstitution."\n");
          //  INSERE NO ARRAY INDIVIDUAL NOME E INSTITUIÇÃO DO AUTOR
          array_push($authorsArray, $authorArray);

          array_push($objAuthorsArray, new Person($authorName, $authorInstitution));
        }
      }
      //print_r($authorsArray);
      //print_r($objAuthorsArray);

      $paperArray['authors'] = $authorsArray;

      // $authorName = str_replace(";", "", $authors[0]->nodeValue);
      // $authorInstitution = $authors[0]->attributes["title"]->nodeValue;


      // print_r(
      //   $id[0]->nodeValue." | "
      //   .$title[0]->nodeValue." | "
      //   .$type[0]->nodeValue
      //   ."\n\n"
      // );

      $objPaper = new Paper(
        $id[0]->nodeValue, 
        $title[0]->nodeValue, 
        $type[0]->nodeValue,
        $objAuthorsArray
      );

      array_push($papersArray, $paperArray);

      array_push($objPapersArray, $objPaper);
    }

    //print_r($papersArray);
    //print_r($objPapersArray);

    //print("\n\n\n\n\n\n\n\n");



    return [
      // new Paper(
      //   123,
      //   'The Nobel Prize in Physiology or Medicine 2023',
      //   'Nobel Prize',
      //   [
      //     new Person('Katalin Karikó', 'Szeged University'),
      //     new Person('Drew Weissman', 'University of Pennsylvania'),
      //   ]
      // ),
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