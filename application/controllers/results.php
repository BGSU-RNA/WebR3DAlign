<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends CI_Controller {

	public function view($query_id)
	{
        $this->load->model('Query_model', '', TRUE);
        $this->load->model('Results_model', '', TRUE);

        $status = $this->Query_model->get_query_status($query_id);

        $data['status']   = $status;
        $data['query_id'] = $query_id;
        $data['title']    = "Query $query_id";
        $data['verbose_footer'] = True;
        $this->load->view('header', $data);
        $this->load->view('menu');

        if ( $status == 'done' ) {
            $data['basepair_table'] = $this->Results_model->get_basepair_comparison($query_id);
            $data['alignment'] = $this->Results_model->get_alignment($query_id);
            $this->load->view('results_view', $data);

        } elseif ( $status == 'submitted' or $status == 'active' ) {
            $this->load->view('interstitial_view', $data);

        } else {
            show_404();
        }

        $this->load->view('footer');
	}
}

/* End of file results.php */
/* Location: ./application/controllers/results.php */