<?php

namespace App\Controller;

use Picqer\Barcode\BarcodeGeneratorPNG;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class BarcodeController
{

    public static function makeBarcode($text, $typeExt)
    {
        $barcode = new BarcodeGenerator();
        $barcode->setText($text);
        $barcode->setType(constant('CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::'.$typeExt));
        $barcode->setScale(2);
        $barcode->setThickness(25);
        $barcode->setFontSize(10);

        return $barcode;

        $redColor = [255, 0, 0];

        $generator = new BarcodeGeneratorPNG();
        //echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128)) . '">';

        return base64_encode($generator->getBarcode("081231723897", $generator::TYPE_CODE_128, 3, 50));
    }
}