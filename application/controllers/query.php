<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Controller {

    public function new_query()
    {
        $this->load->model('Query_model', '', TRUE);

        $query_id = $this->Query_model->new_query();

        print_r($_POST);

        if ( $query_id ) {
            echo "Query $query_id created successfully";
        } else {
            echo 'Query failed';
        }


        redirect("results/$query_id");






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