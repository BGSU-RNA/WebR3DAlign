<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends CI_Controller {

	public function view($query_id)
	{
        $this->load->model('Results_model');
        $data['query_id'] = $query_id;
        $data['basepair_table'] = $this->Results_model->get_basepair_comparison($query_id);
        $data['alignment'] = $this->Results_model->get_alignment($query_id);

		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('results_view', $data);
		$this->load->view('footer');
	}
}

/* End of file results.php */
/* Location: ./application/controllers/results.php */