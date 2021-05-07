<?php

namespace App\Controller;

use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class BarcodeController
{

    public static function makeBarcode($text, $typeExt, $colour)
    {
        $colour == "" ? $colour : "#ffffff";

        $barcode = new BarcodeGenerator();
        $barcode->setText($text);
        $barcode->setType(constant('CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::'.$typeExt));
        $barcode->setBackgroundColor($colour);
        $barcode->setScale(2);
        $barcode->setThickness(25);
        $barcode->setFontSize(10);

        return $barcode;
    }
}