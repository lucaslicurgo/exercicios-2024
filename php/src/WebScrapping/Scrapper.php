<?php

namespace Chuva\Php\WebScrapping;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use DOMXPath;

/**
 * Does the scrapping of a webpage.
 */

require_once 'Entity/Paper.php';
require_once 'Entity/Person.php';
require_once 'vendor/autoload.php';

class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $xPath = new DOMXPath($dom);

    $projetos = $xPath->query('.//a[@class="paper-card p-lg bd-gradient-left"]');

    $papers = [];

    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile('output.xlsx');

    $headerRow = WriterEntityFactory::createRowFromArray([
      'ID', 'Title', 'Type',
      'Author 1', 'Author 1 Institution',
      'Author 2', 'Author 2 Institution',
      'Author 3', 'Author 3 Institution',
      'Author 4', 'Author 4 Institution',
      'Author 5', 'Author 5 Institution',
      'Author 6', 'Author 6 Institution',
      'Author 7', 'Author 7 Institution',
      'Author 8', 'Author 8 Institution',
      'Author 9', 'Author 9 Institution'
    ]);
    $writer->addRow($headerRow);

    foreach ($projetos as $projeto) {

      $title = $xPath->query('.//h4[@class="my-xs paper-title"]', $projeto)->item(0)->textContent;
      $type = $xPath->query('.//div[@class="tags mr-sm"]', $projeto)->item(0)->textContent;
      $id = $xPath->query('.//div[@class="volume-info"]', $projeto)->item(0)->textContent;

      $paper = new Paper($id, $title, $type);

      $autoresNodes = $xPath->query('.//div[@class="authors"]/span', $projeto);
      $authors = [];

      foreach ($autoresNodes as $i => $autorNode) {
        $name = $autorNode->textContent;
        $institution = $autorNode->getAttribute('title');
        $person = new Person($name, $institution);
        $authors[] = $name;
        $authors[] = $institution;
      }

      $row = WriterEntityFactory::createRowFromArray([
        $id, $title, $type,
        $authors[0] ?? '', $authors[1] ?? '',
        $authors[2] ?? '', $authors[3] ?? '',
        $authors[4] ?? '', $authors[5] ?? '',
        $authors[6] ?? '', $authors[7] ?? '',
        $authors[8] ?? '', $authors[9] ?? '',
        $authors[10] ?? '', $authors[11] ?? '',
        $authors[12] ?? '', $authors[13] ?? '',
        $authors[14] ?? '', $authors[15] ?? '',
        $authors[16] ?? '', $authors[17] ?? ''
      ]);

      $writer->addRow($row);

      $papers[] = $paper;
    }

    $writer->close();

    return $papers;
  }
  
}
