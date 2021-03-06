<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Controller {

    public function new_query()
    {
        $this->load->model('Query_model', '', TRUE);
        $this->load->library('upload');

        // unique id for this query
        $query_id = uniqid();

        // redirect right away
        header('Location: ' . base_url("results/$query_id"));

        // create new results folder
        $query_folder = $this->config->item('results_folder') . $query_id;
        mkdir($query_folder);
        chmod($query_folder, 0777);

        // save pdb1 if uploaded
        $this->_save_file('upload_pdb1', $query_id);

        // if pdb2 was uploaded
        $this->_save_file('upload_pdb2', $query_id);

        // write out R3DAlign matlab script
        $this->Query_model->create_r3dalign_script($query_id);

        // save the data in the database
        // this will launch matlab
        $result = $this->Query_model->new_query($query_id);
        if ( $result['status'] and $result['email'] ) {
            // email when precomputed results are available
            $this->notify($query_id, $result['email']);
        }

    }

	private function notify($query_id, $email)
    {
        $this->load->library('email');
        $this->email->set_newline("\r\n");

        $this->email->from('rnabgsu@gmail.com', 'R3D Align');
        $this->email->to($email);

        $this->email->subject("R3D Align results $query_id");

        $message = "Your R3D Align results are available at the following url: "
                    . site_url("results/view/$query_id");

        $this->email->message($message);

        $this->email->send();
    }

    private function _save_file($file_name, $query_id)
    {
        if( isset($_FILES[$file_name]) && !empty($_FILES[$file_name]['name'])) {

            // initialize the uploads library
            $config['upload_path']   = $this->config->item('results_folder') . $query_id;
            $config['allowed_types'] = '*';
            $config['max_size']      = 1024 * 20; // 20 megabytes
            $config['encrypt_name']  = FALSE;

            $extension = '.pdb';

            $config['file_name'] = $file_name . $extension;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($file_name)) {
                echo $this->upload->display_errors();
                // store error code in the database
            }

            if ($file_name == 'upload_pdb1') {
                rename($config['upload_path'] . '/upload_pdb1.pdb', $config['upload_path'] . '/' . $query_id . '_1.pdb');
            } elseif ($file_name == 'upload_pdb2') {
                rename($config['upload_path'] . '/upload_pdb2.pdb', $config['upload_path'] . '/' . $query_id . '_2.pdb');
            }

        } else {
            log_message('error', 'File not found');
        }
    }

	public function view($query_id)
	{
        $this->load->model('Results_model');
        $data['query_id'] = $query_id;
        $data['basepair_table'] = $this->Results_model->get_basepair_comparison($query_id);
        $data['alignment'] = $this->Results_model->get_alignment($query_id);
        $data['title'] = "Query $query_id";

		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('results_view', $data);
		$this->load->view('footer');
	}
}

/* End of file query.php */
/* Location: ./application/controllers/query.php */