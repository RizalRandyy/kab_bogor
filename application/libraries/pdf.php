<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter DomPDF Library
 *
 * Generate PDF's from HTML in CodeIgniter
 */

use Dompdf\Dompdf;
class Pdf extends Dompdf{
    
    /**
     * PDF filename & attachment setting
     */
    public $filename;
    public $attachment;
    public function __construct(){
        parent::__construct();
        $this->attachment = false;
        $this->filename = "laporan.pdf";
    }

    /**
     * Get an instance of CodeIgniter
     *
     * @access    protected
     * @return    void
     */
    protected function ci()
    {
        return get_instance();
    }


    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access    public
     * @param    string    $view The view to load
     * @param    array    $data The view data
     * @return    void
     */
    public function load_view($view, $data = array()){
        $html = $this->ci()->load->view($view, $data, TRUE);

        $this->load_html($html);
        // Render the PDF
        $this->render();
        // Output the generated PDF to Browser
        $this->stream($this->filename, array("Attachment" => $this->attachment));
    }

    public function load_view_for_zip($view, $data = array()){
        $html = $this->ci()->load->view($view, $data, TRUE);

        $this->load_html($html);
        // Render the PDF
        $this->render();
        $this->output();
    }
}