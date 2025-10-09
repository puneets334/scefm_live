<?php

use setasign\Fpdi;

class Pdf extends Fpdi\TcpdfFpdi
{
    /**
     * "Remembers" the template id of the imported page
     */
    var $_pdf_path_main;
    var $_tplIdx;


    function __construct($pdf_path) {
        parent::__construct();
        $this->_pdf_path_main = $pdf_path;
    }

    /**
     * Draw an imported PDF logo on every page
     */

    function Header() {

        if (is_null($this->_tplIdx)) {

            // THIS IS WHERE YOU GET THE NUMBER OF PAGES
            $this->numPages = $this->setSourceFile($this->_pdf_path_main);
            $this->_tplIdx = $this->importPage(1);

        }
        $this->useTemplate($this->_tplIdx);

    }

    function Footer()
    {
        // emtpy method body
    }
}