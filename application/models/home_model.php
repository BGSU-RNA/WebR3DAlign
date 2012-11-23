<?php
class Home_model extends CI_Model {

    function __construct()
    {
        $CI = & get_instance();
        parent::__construct();
        $this->load->database();
    }

    function get_all_pdbs()
    {
        $url = 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_all_rna_pdb_ids';
        $response = file_get_contents($url);
        $pdbs = json_decode($response);
        return $pdbs->pdb_ids;
    }

}

/* End of file home_model.php */
/* Location: ./application/model/home_model.php */