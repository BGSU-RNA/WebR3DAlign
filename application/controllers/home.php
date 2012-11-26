<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
        $this->load->model('Home_model');
        $data['title'] = 'R3DAlign';

        $data['pdbs'] = $this->Home_model->get_all_pdbs();

        $data['verbose_footer'] = False;
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('home_view', $data);
		$this->load->view('footer', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */