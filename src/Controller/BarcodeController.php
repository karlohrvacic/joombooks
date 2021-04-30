<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class BarcodeController{

    public function makeISBN($ISBNcode)
    {

        $barcode = new BarcodeGenerator();
        $barcode->setText($ISBNcode);
        $barcode->setType(BarcodeGenerator::Isbn);
        $barcode->setScale(2);
        $barcode->setThickness(25);
        $barcode->setFontSize(10);

        #echo '<img src="data:image/png;base64,'.$code.'" />';
        return $barcode;
    }
}