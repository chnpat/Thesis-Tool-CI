<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
function pdf_create($html, $filename='', $stream=TRUE) 
{
    require_once("dompdf/autoload.inc.php");
    
    $dompdf = new Dompdf();
    //$dompdf->set_base_path(CSS."bootstrap.min.css");
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf", array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}
?>