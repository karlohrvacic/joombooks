<?php

namespace App\Controller;

use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class BarcodeController
{

    public function makeBarcode($text, $typeExt)
    {
        $barcode = new BarcodeGenerator();
        $barcode->setText($text);
        $barcode->setType(constant('CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::'.$typeExt));
        $barcode->setScale(2);
        $barcode->setThickness(25);
        $barcode->setFontSize(12);
        return $barcode;
    }
}