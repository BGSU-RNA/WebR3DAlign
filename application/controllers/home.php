<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
        $data['title'] = 'R3D Align';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('home_view');
		$this->load->view('footer', $data);
	}

	public function gallery()
	{
	    header( 'Location: http://rna.bgsu.edu/main/r3dalign-help/gallery-of-featured-alignments/' ) ;
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */