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
        $this->db->select('structureId')
                 ->from('pdb_info')
                 ->distinct();
        $query = $this->db->get();

        foreach($query->result() as $row) {
            $pdbs[] = $row->structureId;
        }

        return $pdbs;
    }

}

/* End of file home_model.php */
/* Location: ./application/model/home_model.php */