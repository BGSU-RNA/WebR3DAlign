<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends CI_Controller {

	public function index()
	{
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('results_view');
		$this->load->view('footer');
	}
}

/* End of file results.php */
/* Location: ./application/controllers/results.php */