<?php
class Query_model extends CI_Model {

    function __construct()
    {
        $CI = & get_instance();
        // Call the Model constructor
        parent::__construct();
    }

    function new_query($query_id)
    {
        $name1 = 'upload_pdb1';
        $name2 = 'upload_pdb2';
        $seed  = 'seed_uploaded';
        $pdb_uploaded1 = (isset($_FILES[$name1]) && !empty($_FILES[$name1]['name'])) ? 1 : NULL;
        $pdb_uploaded2 = (isset($_FILES[$name2]) && !empty($_FILES[$name2]['name'])) ? 1 : NULL;
        $seed_uploaded = (isset($_FILES[$seed])  && !empty($_FILES[$seed]['name']))  ? 1 : NULL;

        $data = array(
            'query_id' => $query_id,
            'status' => '0',
            'email' => $this->input->post('email') ? $this->input->post('email') : NULL,

            'time_submitted' => date("Y-m-d H:m:s"),
            'time_completed' => NULL,

            'pdb1' => $this->input->post('pdb1'),
            'pdb_uploaded1' => $pdb_uploaded1,
            'pdb2' => $this->input->post('pdb2'),
            'pdb_uploaded2' => $pdb_uploaded2,

            'seed'          => $this->input->post('seed'),
            'seed_uploaded' => $seed_uploaded,

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
        } catch (Exception $e) {
            return FALSE;
        }

        return TRUE;
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

    function get_query_status($query_id)
    {
        $this->db->select()
                 ->from('query')
                 ->where('query_id', $query_id);
        $query = $this->db->get();

        if ($query->num_rows() == 0 ) {
            return 'not found';
        } else {
            $result = $query->row();
            if ( $result->status == 0 ) {
                return 'submitted';
            } elseif ( $result->status < 0 ) {
                return 'aborted';
            } elseif ( $result->status == 1 ) {
                return 'done';
            } else {
                return 'unknown';
            }
        }

    }

    function create_r3dalign_script($query_id)
    {
        $filename = './data/results/' . $query_id . '/query.m';
        $fh = fopen($filename, 'w') or die("Can't open Query file");

        fwrite($fh, "Query.Name = '{$query_id}';\n");

        if ( $this->input->post('email') ) {
            $email = $this->input->post('email');
            fwrite($fh, "Query.Email = '{$email}';\n");
        }

        $name1 = 'upload_pdb1';
        $name2 = 'upload_pdb2';

        if( isset($_FILES[$name1]) && !empty($_FILES[$name1]['name'])) {
            fwrite($fh, "pdb1  = 'uploaded';\n");
            fwrite($fh, "Name1 = '{$name1}.pdb';\n");
            fwrite($fh, "Query.UploadName1 = '$name1';\n");
        } else {
            $pdb1 = $this->input->post('pdb1');
            fwrite($fh, "pdb1 = '$pdb1';\n");
        }

        if( isset($_FILES[$name2]) && !empty($_FILES[$name2]['name'])) {
            fwrite($fh, "pdb2 = 'uploaded';\n");
            fwrite($fh, "Name2 = '{$name2}.pdb';\n");
            fwrite($fh, "Query.UploadName2 = '$name2';\n");
        } else {
            $pdb2 = $this->input->post('pdb2');
            fwrite($fh, "pdb2 = '$pdb2';\n");
        }

        $chains = $this->input->post('mol1_chains');
        $i = 1;
        foreach($chains as $chain) {
            fwrite($fh, 'Chain1{' . $i . "} = '");
            fwrite($fh, $_POST["mol1_chains"][$i-1] . "';\n");
            fwrite($fh, 'Nts1{' . $i . "}   = '");
            fwrite($fh, $_POST["mol1_nts"][$i-1] . "';\n");
            $i++;
        }

        $chains = $this->input->post('mol2_chains');
        $i = 1;
        foreach($chains as $chain) {
            fwrite($fh, 'Chain2{' . $i . "} = '");
            fwrite($fh, $_POST["mol2_chains"][$i-1] . "';\n");
            fwrite($fh, 'Nts2{' . $i . "}   = '");
            fwrite($fh, $_POST["mol2_nts"][$i-1] . "';\n");
            $i++;
        }

        $discrepancy1   = $this->input->post('discrepancy1');
        $neighborhoods1 = $this->input->post('neighborhoods1');
        $bandwidth1     = $this->input->post('bandwidth1');
        $seed1 = $this->input->post('seed');
        $clique_method1 = $this->input->post('clique_method1');
        $iteration2 = $this->input->post('iteration_enabled2');

        if ( $seed1 == 'Manual' ) {
            fwrite($fh, "Query.SeedName = 'seed.txt';\n");
        } else {
            fwrite($fh, "Query.SeedName = '';\n");
        }

        $text = <<<EOD
Disc1     = $discrepancy1;
NeighMin1 = $neighborhoods1;
Band1     = $bandwidth1;
CliqMeth1 = '$clique_method1';
Seed1     = '$seed1';
[AlNTs1,AlNTs2] = webR3DAlign(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc1,NeighMin1,Band1,CliqMeth1,Query);
EOD;

        fwrite($fh, $text);

        if ( $this->input->post('iteration_enabled2') ) {
            $discrepancy2   = $this->input->post('discrepancy2');
            $neighborhoods2 = $this->input->post('neighborhoods2');
            $bandwidth2     = $this->input->post('bandwidth2');
            $clique_method2 = $this->input->post('clique_method2');

            $text = <<<EOD
Disc2     = $discrepancy2;
NeighMin2 = $neighborhoods2;
Band2     = $bandwidth2;
CliqMeth2 = $clique_method2;
[AlNTs3,AlNTs4] = webR3DAlign(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc2,NeighMin2,Band2,CliqMeth2,Query,AlNTs1,AlNTs2);
EOD;
            fwrite($fh, $text);

            if ( $this->input->post('iteration_enabled2') ) {
                $discrepancy3   = $this->input->post('discrepancy3');
                $neighborhoods3 = $this->input->post('neighborhoods3');
                $bandwidth3     = $this->input->post('bandwidth3');
                $clique_method3 = $this->input->post('clique_method3');

                $text = <<<EOD
Disc3     = $discrepancy3;
NeighMin3 = $neighborhoods3;
Band3     = $bandwidth3;
CliqMeth3 = $clique_method3;
[AlNTs5,AlNTs6] = webR3DAlign(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc3,NeighMin3,Band3,CliqMeth3,Query,AlNTs3,AlNTs4);
EOD;
                fwrite($fh, $text);
            }
        }

        fclose($fh);

    }


}