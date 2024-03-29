<?php
class Query_model extends CI_Model {

    function __construct()
    {
        $CI = & get_instance();
        // Call the Model constructor
        parent::__construct();
    }

    function get_email($query_id)
    {
        $this->db->select('email')
                 ->from('query')
                 ->where('query_id', $query_id);
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ) {
            $row = $query->row();
            return $row->email;
        } else {
            return FALSE;
        }
    }

    function _remove_duplicates($nts, $chains)
    {
        // helps to avoid duplicate entries where the same chain with the same
        // nucleotides are submitted, e.g. chains A,A nts all,all
        if ( $nts and $chains ) {
            $observed = array();
            foreach($nts as $k => $v) {
                $combination = $v . $chains[$k]; // chain + nt combination
                if ( in_array($combination, $observed) ) {
                    unset($nts[$k]); // delete duplicate from both arrays
                    unset($chains[$k]);
                } else {
                    $observed[] = $combination;
                }
            }
        }

        return array('nts' => $nts, 'chains' => $chains);
    }

    function new_query($query_id)
    {
        //Comment out code allowing the user to upload files
        //$name1 = 'upload_pdb1';
        //$name2 = 'upload_pdb2';

        //$pdb_uploaded1 = (isset($_FILES[$name1]) && !empty($_FILES[$name1]['name'])) ? 1 : NULL;
        //$pdb_uploaded2 = (isset($_FILES[$name2]) && !empty($_FILES[$name2]['name'])) ? 1 : NULL;

        //$pdb_uploaded_filename1 = (isset($_FILES[$name1]) && !empty($_FILES[$name1]['name'])) ? $_FILES[$name1]['name'] : NULL;
        //$pdb_uploaded_filename2 = (isset($_FILES[$name2]) && !empty($_FILES[$name2]['name'])) ? $_FILES[$name2]['name'] : NULL;

        //Always NULL
        $pdb_uploaded1 = NULL;
        $pdb_uploaded2 = NULL;
        $pdb_uploaded_filename1 = NULL;
        $pdb_uploaded_filename2 = NULL;

        $nt_ch1 = $this->_remove_duplicates($this->input->post('mol1_nts'),
                                            $this->input->post('mol1_chains'));
        $nt_ch2 = $this->_remove_duplicates($this->input->post('mol2_nts'),
                                            $this->input->post('mol2_chains'));

        $data = array(
            'query_id' => $query_id,
            'status' => '0',
            'email' => $this->input->post('email') ? $this->input->post('email') : NULL,

            'time_completed' => NULL,

            'pdb1' => $this->input->post('pdb1'),
            'pdb_uploaded1' => $pdb_uploaded1,
            'pdb2' => $this->input->post('pdb2'),
            'pdb_uploaded2' => $pdb_uploaded2,

            'pdb_uploaded_filename1' => $pdb_uploaded_filename1,
            'pdb_uploaded_filename2' => $pdb_uploaded_filename2,

            'seed'          => 'NWseed',
            'seed_uploaded' => 0,

            'nts1' => $nt_ch1['nts'] ? implode(';', $nt_ch1['nts']) : NULL,
            'nts2' => $nt_ch2['nts'] ? implode(';', $nt_ch2['nts']) : NULL,
            'chains1' => $nt_ch1['chains'] ? implode(';', $nt_ch1['chains']) : NULL,
            'chains2' => $nt_ch2['chains'] ? implode(';', $nt_ch2['chains']) : NULL
        );

        $data = array_merge($data, $this->_get_iteration1());
        $data = array_merge($data, $this->_get_iteration2());
        $data = array_merge($data, $this->_get_iteration3());

        // check if such a query has already been performed
        if ($this->_precomputed_results_exist($data)) {
            $data['status'] = 1;
            $data['time_submitted'] = date("Y-m-d H:m:s");
            $data['time_completed'] = date("Y-m-d H:m:s");
            $email = $data['email'];
        } else {
            $email = FALSE;
        }

        try {
            $this->db->insert('query', $data);
            $dbstatus = TRUE;
        } catch (Exception $e) {
            $dbstatus = FALSE;
        }

        return array('status' => $dbstatus, 'email' => $email);
    }


    private function _precomputed_results_exist($data) {

        if ( $data['pdb_uploaded1'] == 1 or $data['pdb_uploaded2'] == 1 ) {
            return FALSE;
        }

        $this->db->select()
                 ->from('query')
                 ->where('status', 1) // successful queries
                 ->order_by('time_completed', 'desc') // get most recent results
                 ->limit(1);

        $ignore = array('id', 'query_id', 'time_submitted', 'time_completed',
                        'status', 'email');

        // populate sql query with fields from the query
        foreach($data as $key => $value) {
            if ( !in_array($key, $ignore) ) {
                $this->db->where($key, $value);
            }
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {

            $result = $query->row();

            // make sure the result doesn't have to be recomputed
            $this->db->select()
                     ->from('critical_updates')
                     ->where('date >', $result->time_completed)
                     ->limit(1);
            $query2 = $this->db->get();
            if ( $query2->num_rows() ) {
                return FALSE;
            }

            // copy over all resulting files with new names
            $source = $this->config->item('results_folder') . $result->query_id;
            $sourceHandle = opendir($source);

            while ( $file = readdir($sourceHandle) ){
                if ( $file == '.' || $file == '..' ) {
                    continue;
                } elseif ( is_file($source . '/' . $file) ) {

                    // for filenames like <query_id>_int.png
                    $pattern = '/^\w{13}(\w+)\./';
                    if ( preg_match($pattern, $file, $matches) ) {
                        $suffix = $matches[1];
                    } else {
                        $suffix = '';
                    }

                    $src = $source . '/' . $file;
                    $ext = pathinfo($src, PATHINFO_EXTENSION);
                    $query_id = $data['query_id'];
                    $dst = $this->config->item('results_folder') . $query_id . '/' . $query_id . $suffix . '.' . $ext;

                    copy($src, $dst);
                }
            }

            return TRUE;
        } else {
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
                'clique_method1' => 'greedy',
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
                'clique_method2' => 'greedy'
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
                'clique_method3' => 'greedy',
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
            $status = 'not found';
            $time_submitted = '';
            $time_completed = '';
        } else {
            $result = $query->row();
            if ( $result->status == 0 ) {
                $status = 'submitted';
            } elseif ( $result->status == -1 ) {
                $status = 'aborted';
            } elseif ( $result->status == -2 ) {
                $status = 'crashed';
            } elseif ( $result->status == 1 ) {
                $status = 'done';
            } elseif ( $result->status == 2 ) {
                $status = 'active';
            } else {
                $status = 'unknown';
            }
            $time_submitted = $result->time_submitted;
            $time_completed = $result->time_completed;
        }
        return array('status'         => $status,
                     'time_submitted' => $time_submitted,
                     'time_completed' => $time_completed);

    }

    function create_r3dalign_script($query_id)
    {
        $filename = './data/results/' . $query_id . '/query.m';
        $fh = fopen($filename, 'w') or die("Can't open Query file");

        fwrite($fh, "Query.Name = '{$query_id}';\n");
        fwrite($fh, "Query.Type = 'web';\n");
        fwrite($fh, "Query.LoadFinal = 0;\n");

        if ( $this->input->post('email') ) {
            $email = $this->input->post('email');
            fwrite($fh, "Query.Email = '{$email}';\n");
        }

        $name1 = 'upload_pdb1';
        $name2 = 'upload_pdb2';

        if( isset($_FILES[$name1]) && !empty($_FILES[$name1]['name'])) {
            $pdb_uploaded1 = TRUE;
            fwrite($fh, "pdb1  = 'uploaded';\n");
            fwrite($fh, "Name1 = '{$query_id}_1.pdb';\n");
            fwrite($fh, "Query.UploadName1 = '{$query_id}_1';\n");
        } else {
            $pdb_uploaded1 = FALSE;
            $pdb1 = $this->input->post('pdb1');
            fwrite($fh, "pdb1 = '$pdb1';\n");
        }

        if( isset($_FILES[$name2]) && !empty($_FILES[$name2]['name'])) {
            $pdb_uploaded2 = TRUE;
            fwrite($fh, "pdb2 = 'uploaded';\n");
            fwrite($fh, "Name2 = '{$query_id}_2.pdb';\n");
            fwrite($fh, "Query.UploadName2 = '{$query_id}_2';\n");
        } else {
            $pdb_uploaded2 = FALSE;
            $pdb2 = $this->input->post('pdb2');
            fwrite($fh, "pdb2 = '$pdb2';\n");
        }

        // Chains and nucleotides 1
        $nt_ch1 = $this->_remove_duplicates($this->input->post('mol1_nts'),
                                            $this->input->post('mol1_chains'));
        $nts    = $nt_ch1['nts'];
        $chains = $nt_ch1['chains'];

        $i = 1;
        foreach($chains as $chain) {
            fwrite($fh, 'Chain1{' . $i . "} = '");
            fwrite($fh, $chains[$i-1] . "';\n");
            fwrite($fh, 'Nts1{' . $i . "}   = '");
            fwrite($fh, trim($nts[$i-1]) . "';\n");
            $i++;
        }

        // Chains and nucleotides 2
        $nt_ch2 = $this->_remove_duplicates($this->input->post('mol2_nts'),
                                            $this->input->post('mol2_chains'));
        $nts    = $nt_ch2['nts'];
        $chains = $nt_ch2['chains'];

        $i = 1;
        foreach($chains as $chain) {
            fwrite($fh, 'Chain2{' . $i . "} = '");
            fwrite($fh, $chains[$i-1] . "';\n");
            fwrite($fh, 'Nts2{' . $i . "}   = '");
            fwrite($fh, trim($nts[$i-1]) . "';\n");
            $i++;
        }

        $discrepancy1   = $this->input->post('discrepancy1');
        $neighborhoods1 = $this->input->post('neighborhoods1');
        $bandwidth1     = $this->input->post('bandwidth1');
        $seed = 'NWseed';
        $clique_method1 = 'greedy';
        $iteration2 = $this->input->post('iteration_enabled2');

        if ( $seed == 'Manual' ) {
            fwrite($fh, "Query.SeedName = 'seed.txt';\n");
        } else {
            fwrite($fh, "Query.SeedName = '';\n");
        }

        $text = <<<EOD
Disc{1}     = $discrepancy1;
NeighMin{1} = $neighborhoods1;
Band{1}     = $bandwidth1;
CliqMeth{1} = '$clique_method1';
EOD;

        fwrite($fh, "$text\n");

        if ( $this->input->post('iteration_enabled2') ) {
            $discrepancy2   = $this->input->post('discrepancy2');
            $neighborhoods2 = $this->input->post('neighborhoods2');
            $bandwidth2     = $this->input->post('bandwidth2');
            $clique_method2 = 'greedy';

            $text = <<<EOD
Disc{2}     = $discrepancy2;
NeighMin{2} = $neighborhoods2;
Band{2}     = $bandwidth2;
CliqMeth{2} = '$clique_method2';
EOD;
            fwrite($fh, "$text\n");

            if ( $this->input->post('iteration_enabled3') ) {
                $discrepancy3   = $this->input->post('discrepancy3');
                $neighborhoods3 = $this->input->post('neighborhoods3');
                $bandwidth3     = $this->input->post('bandwidth3');
                $clique_method3 = 'greedy';

                $text = <<<EOD
Disc{3}     = $discrepancy3;
NeighMin{3} = $neighborhoods3;
Band{3}     = $bandwidth3;
CliqMeth{3} = '$clique_method3';
EOD;
                fwrite($fh, "$text\n");
            }
        }

        $text = 'webWrapper(pdb1,Chain1,Nts1, pdb2,Chain2,Nts2, Disc,NeighMin,Band,CliqMeth,Query);';
        fwrite($fh, "$text\n");
        fclose($fh);

    }


}