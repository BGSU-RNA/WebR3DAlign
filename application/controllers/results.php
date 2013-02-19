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
        $this->load->view('header', $data);
        $this->load->view('menu');

        if ( $status == 'done' ) {
            $data['results_folder'] = $this->config->item('results_folder');
            $data['basepair_table'] = $this->Results_model->get_basepair_comparison($query_id);
            $data['alignment'] = $this->Results_model->get_alignment($query_id);
            $data['parameters'] = $this->Results_model->get_query_parameters($query_id);
            $this->load->view('results_view', $data);

        } elseif ( $status == 'submitted' or $status == 'active' ) {
            $this->load->view('interstitial_view', $data);

        } elseif ( $status == 'aborted' ) {
            $this->load->view('aborted_view', $data);
            $email = $this->Query_model->get_email($query_id);
            if ( $email ) {
                $this->notify($query_id, $email);
            }

        } elseif ( $status == 'crashed' ) {
            $data['error_message'] = $this->get_error_message($query_id);
            $this->load->view('crashed_view', $data);

        } else {
            show_404();
        }

        $this->load->view('footer');
	}

    private function get_error_message($query_id)
    {
        $line = -1;
        $file = $this->config->item('results_folder') . "{$query_id}/{$query_id}_error.txt";

        if ( file_exists($file) ) {
            $f = fopen($file, 'r');
            $line = fgets($f);
            fclose($f);
        }
        return $line;
    }

	private function notify($query_id, $email)
    {
        $this->load->library('email');
        $this->email->set_newline("\r\n");

        $this->email->from('rnabgsu@gmail.com', 'R3D Align');
        $this->email->to($email);

        $this->email->subject("R3D Align query $query_id has been aborted");

        $message = "Your R3D Align query has been aborted. For more information please visit "
                    . site_url("results/$query_id");

        $this->email->message($message);

        $this->email->send();
    }

}

/* End of file results.php */
/* Location: ./application/controllers/results.php */