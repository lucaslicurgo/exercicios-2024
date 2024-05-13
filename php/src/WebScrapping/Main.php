<?php

namespace Chuva\Php\WebScrapping;

class Main
{


  public static function run(): void
  {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);


    print_r($data);
  }
}
