<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class BarcodeController extends AbstractController
{

    private $barcode;

    public function makeBarcode($text, $typeExt): BarcodeGenerator
    {
        $this->barcode = new BarcodeGenerator();
        $this->barcode->setText($text);
        $this->barcode->setType(constant('CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::'.$typeExt));
        $this->barcode->setScale(2);
        $this->barcode->setThickness(25);
        $this->barcode->setFontSize(10);
        return $this->barcode;
    }
}