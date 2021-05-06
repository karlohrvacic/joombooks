<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class BarcodeController
{


    public static function makeBarcode($text, $typeExt): BarcodeGenerator
    {
        $barcode = new BarcodeGenerator();
        $barcode->setText($text);
        $barcode->setType(constant('CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::'.$typeExt));
        $barcode->setScale(2);
        $barcode->setThickness(25);
        $barcode->setFontSize(10);
        return $barcode;
    }
}