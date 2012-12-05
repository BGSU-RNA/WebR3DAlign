<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Controller {

    public function new_query()
    {
        // unique id for this query
        $query_id = uniqid();

        print_r($_POST);

        echo $query_id;

        // redirect right away
//         redirect("results/$query_id");

        // create new results folder
        $query_folder = '/Servers/rna.bgsu.edu/r3dalign_dev/data/results/' . $query_id;
        mkdir($query_folder);
        chmod($query_folder, 0777);

        $this->load->library('upload');

        // save pdb1 if uploaded
        $this->_save_file('upload_pdb1', $query_id);

        // if pdb2 was uploaded
        $this->_save_file('upload_pdb2', $query_id);

        // if seed was uploaded
        $this->_save_file('seed_upload_file', $query_id);

        // save the data in the database
        // this will trigger the queue
        $this->load->model('Query_model', '', TRUE);
        $result = $this->Query_model->new_query($query_id);
        if (!$result) {
            // query wasn't persisted in the database
        }

    }

    private function _save_file($file_name, $query_id)
    {
        if( isset($_FILES[$file_name]) && !empty($_FILES[$file_name]['name'])) {

            // initialize the uploads library
            $config['upload_path']   = './data/results/' . $query_id;
            $config['allowed_types'] = '*';
            $config['max_size']      = 1024 * 20; // 20 megabytes
            $config['encrypt_name']  = FALSE;
            $config['file_name'] = $file_name;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($file_name)) {
                // store error code in the database
            }
        }
    }

	public function view($query_id)
	{
        $this->load->model('Results_model');
        $data['query_id'] = $query_id;
        $data['basepair_table'] = $this->Results_model->get_basepair_comparison($query_id);
        $data['alignment'] = $this->Results_model->get_alignment($query_id);
        $data['title'] = "Query $query_id";
        $data['verbose_footer'] = True;

		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('results_view', $data);
		$this->load->view('footer');
	}
}

/* End of file query.php */
/* Location: ./application/controllers/query.php */