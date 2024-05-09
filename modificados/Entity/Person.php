<?php

namespace Chuva\Php\WebScrapping\Entity;

/**
 * Paper Author personal information.
 */
class Person {

  /**
   * Person name.
   */
  public string $name;

  /**
   * Person institution.
   */
  public string $institution;

  /**
   * Builder.
   */
  public function __construct($nameParametro, $institutionParametro) {
    $this->name = $nameParametro;
    $this->institution = $institutionParametro;
  }

}
