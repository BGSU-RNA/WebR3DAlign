<?php
class Query_model extends CI_Model {

    function __construct()
    {
        $CI = & get_instance();
        // Call the Model constructor
        parent::__construct();
    }

    function new_query()
    {

        $data = array(
            'query_id' => uniqid(),
            'status' => '0',
            'email' => $this->input->post('email') ? $this->input->post('email') : NULL,

            'time_submitted' => date("Y-m-d H:m:s"),
            'time_completed' => NULL,

            'pdb1' => $this->input->post('pdb1'),
            'pdb_uploaded1' => $this->input->post('pdb_uploaded1'),
            'pdb2' => $this->input->post('pdb2'),
            'pdb_uploaded2' => $this->input->post('pdb_uploaded2'),

            'seed'          => $this->input->post('seed'),
            'seed_uploaded' => $this->input->post('seed_uploaded'),

            'nts1' => implode(';', $this->input->post('mol1_nts')),
            'nts2' => implode(';', $this->input->post('mol2_nts')),
            'chains1' => implode(';', $this->input->post('mol1_chains')),
            'chains2' => implode(';', $this->input->post('mol2_chains'))
        );

        $data = array_merge($data, $this->_get_iteration1());
        $data = array_merge($data, $this->_get_iteration2());
        $data = array_merge($data, $this->_get_iteration3());

        try {
            $this->db->insert('query', $data);
            return $data['query_id'];
        } catch (Exception $e) {
            return FALSE;
        }
    }

    private function _get_iteration1()
    {
        if ( $this->input->post('iteration_enabled1') ) {
            return array(
                'iteration1'     => TRUE,
                'discrepancy1'   => $this->input->post('discrepancy1'),
                'neighborhoods1' => $this->input->post('neighborhoods1'),
                'bandwidth1'     => $this->input->post('bandwidth1'),
                'clique_method1' => $this->input->post('clique_method1'),
            );
        } else {
            return array(
                'iteration1'     => FALSE,
                'discrepancy1'   => NULL,
                'neighborhoods1' => NULL,
                'bandwidth1'     => NULL,
                'clique_method1' => NULL
            );
        }
    }

    private function _get_iteration2()
    {
        if ( $this->input->post('iteration_enabled2') ) {
            return array(
                'iteration2'     => TRUE,
                'discrepancy2'   => $this->input->post('discrepancy2'),
                'neighborhoods2' => $this->input->post('neighborhoods2'),
                'bandwidth2'     => $this->input->post('bandwidth2'),
                'clique_method2' => $this->input->post('clique_method2')
            );
        } else {
            return array(
                'iteration2'     => FALSE,
                'discrepancy2'   => NULL,
                'neighborhoods2' => NULL,
                'bandwidth2'     => NULL,
                'clique_method2' => NULL
            );
        }
    }

    private function _get_iteration3()
    {
        if ( $this->input->post('iteration_enabled3') ) {
            return array(
                'iteration3'     => TRUE,
                'discrepancy3'   => $this->input->post('discrepancy3'),
                'neighborhoods3' => $this->input->post('neighborhoods3'),
                'bandwidth3'     => $this->input->post('bandwidth3'),
                'clique_method3' => $this->input->post('clique_method3'),
            );
        } else {
            return array(
                'iteration3'     => FALSE,
                'discrepancy3'   => NULL,
                'neighborhoods3' => NULL,
                'bandwidth3'     => NULL,
                'clique_method3' => NULL,
            );
        }
    }


}