<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends CI_Controller {

	public function index()
	{
        $row = 1;
        if (($handle = fopen("/Servers/rna.bgsu.edu/r3dalign_dev/data/spreadsheets/4d24d95bee03d/4d24d95bee03d.csv", "r")) !== FALSE) {
            $results['data'] = '<table class="table table-bordered table-hover table-condensed">';
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row++;
                $results['data'] .= '<tr>';
                for ($c=0; $c < $num; $c++) {
                    $results['data'] .= '<td>' . $data[$c] . '</td>';
                }
                $results['data'] .= '</tr>';
            }
            $results['data'] .= '</table>';
            fclose($handle);
        }

		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('results_view', $results);
		$this->load->view('footer');
	}
}

/* End of file results.php */
/* Location: ./application/controllers/results.php */