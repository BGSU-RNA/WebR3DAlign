<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
        $data['title'] = 'R3DAlign';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('home_view');
		$this->load->view('footer', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */