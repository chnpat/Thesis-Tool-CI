<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/** import the DOMPDF library for using. */
use Dompdf\Dompdf;

/**
 *  pdf_create() is a callable function for rendering a PDF file from the HTML string.
 *  
 *  pdf_create() is a callable function for rendering a PDF file from the HTML string by using
 *  predefined functions from the DOMPDF library.
 *
 *  @param      string      $string         A HTML string that will be rendered content in the PDF file.
 *  @param      string      $filename       A string of the output PDF file name.
 *  @param      boolean     $stream         A boolean indicator to indicate whether the output PDF file is 
 *                                          stream on the web browser or download as an outfile file.
 *
 *  @return     file                        An output file after rendering which will be download into the 
 *                                          user's local computer.
 */
function pdf_create($html, $filename='', $stream=TRUE) 
{
    require_once("dompdf/autoload.inc.php");
    
    /** Initiates a DOMPDF library instance. */
    $dompdf = new Dompdf();

    /** Loads the HTML string into the DOMPDF instance. */
    $dompdf->loadHtml($html);

    /** Sets the output page size and page orientation. */
    $dompdf->setPaper('letter', 'portrait');

    /** Assigns a DOMPDF instance to render the PDF file. */
    $dompdf->render();

    /** Select either the output PDF file will be streamed or download. */
    if ($stream) {

        /** If the streaming are selected, streams the PDF file on user's web browser. */
        $dompdf->stream($filename.".pdf", array('Attachment' => 0));
    } else {
        /** Returns the output PDF file and downloads to user's local computer. */
        return $dompdf->output();
    }
}
?>