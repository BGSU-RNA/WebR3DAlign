<?php
class Results_model extends CI_Model {

    function __construct()
    {
        $CI = & get_instance();
        parent::__construct();
    }

    function get_query_parameters($query_id)
    {
        $this->db->select()
                 ->from('query')
                 ->where('query_id', $query_id);
        $result = $this->db->get()->result_array();
        return $result[0];
    }

    function _get_discrepancy_css_class($disc)
    {
        $class = '';

        if ( $disc < 0.1 ) {
            $class = 'd10';
        } elseif ( $disc < 0.2 ) {
            $class = 'd09';
        } elseif ( $disc < 0.3 ) {
            $class = 'd08';
        } elseif ( $disc < 0.4 ) {
            $class = 'd07';
        } elseif ( $disc < 0.5 ) {
            $class = 'd06';
        } elseif ( $disc < 0.6 ) {
            $class = 'd05';
        } elseif ( $disc < 0.7 ) {
            $class = 'd04';
        } elseif ( $disc < 0.8 ) {
            $class = 'd03';
        } elseif ( $disc < 0.9 ) {
            $class = 'd02';
        } elseif ( $disc < 1.0 ) {
            $class = 'd01';
        } else {
            $class = 'd00';
        }
        return $class;
    }

    function _format_discrepancy($disc)
    {
        $class = $this->_get_discrepancy_css_class($disc);
        return "<td class='$class disc-value'></td>";
    }

    function get_basepair_comparison($query_id)
    {
        $filename = $this->config->item('results_folder') . "$query_id/{$query_id}.csv";
        if ( !file_exists($filename) ) {
            return NULL;
        }

        $table = '';
        if (($handle = fopen($filename, "r")) !== FALSE) {
            $isFirstLine = True;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($isFirstLine) {
                    $table .= '<thead>';
                }
                $table .= '<tr>';
                $num = count($data);
                for ($i = 0; $i < $num; $i++) {
                    $data[$i] = trim($data[$i]);
                    if ( $isFirstLine ) {
                        $tag = 'th';
                    } else {
                        $tag = 'td';
                    }
                    if ($tag == 'th' and $data[$i] == 'Discrepancy') {
                        $table .= '<th>Disc</th>';
                    } elseif ( $i > 6 ) {
                        continue;
                    } elseif ( $i == 1 or $i == 4 ) {
                        $table .= "<$tag class='{$data[$i]}'>" . $data[$i] . "</$tag>";
                    } elseif ( $i == 6 and $tag == 'td') {
                        // prevents writing out zeros for empty discrepancy fields
                        if ( $data[$i] != '' ) {
                            $table .= $this->_format_discrepancy($data[$i]);
                        }
                    } else {
                        $table .= "<$tag>" . $data[$i] . "</$tag>";
                    }

                }
                $table .= '</tr>';
                if ($isFirstLine) {
                    $table .= '</thead><tbody>';
                    $isFirstLine = False;
                }
            }
            $table .= '</tbody>';
            fclose($handle);
        }
        return $table;
    }

    function get_alignment($query_id)
    {
        $filename = $this->config->item('results_folder') . "$query_id/{$query_id}.fasta";
        if ( !file_exists($filename) ) {
            return NULL;
        }

        $lines = file($filename);

        $num = count($lines) - 1; // the last line is empty
        $delta = $num / 2;

        $alignment = '';

        for ($i = 1; $i <= $delta; $i++) {
            $alignment .= $lines[$i] . $lines[$i+$delta+1] . "\n";
        }

        return $alignment;

    }

    function get_summary_table($query_id)
    {
        $filename = $this->config->item('results_folder') . "$query_id/{$query_id}_stats.csv";
        if ( !file_exists($filename) ) {
            return NULL;
        }

        $lines = file($filename);

        $summary_stats_dict = array(
            "Number of nucleotides in structure 1"          => 'num_nt1',
            "Number of nucleotides in structure 2"          => 'num_nt2',
            "Number of nucleotides aligned"                 => 'num_nt_aligned',
            "Percentage of structure 1 nucleotides aligned" => 'perc_nt_aligned1',
            "Percentage of structure 2 nucleotides aligned" => 'perc_nt_aligned2',
            "Number of basepairs in structure 1"            => 'num_bp1',
            "Number of basepairs in structure 2"            => 'num_bp2',
            "Number of basepairs aligned"                   => 'num_bp_aligned',
            "Percentage of structure 1 basepairs aligned"   => 'perc_bp_aligned1',
            "Percentage of structure 2 basepairs aligned"   => 'perc_bp_aligned2',
            "Mean local neighborhood discrepancy"           => 'local_disc',
            "Global discrepancy of all aligned nucleotides" => 'global_disc'
        );

        $data = array();

        foreach($lines as $line){
            list($key, $value) = explode(',', $line);
            // if the key is not defined, it will be skipped
            if ( array_key_exists($key, $summary_stats_dict) ) {
                $data[$summary_stats_dict[$key]] = $value;
            }
        }

        return $this->_generate_summary_table($data);
    }

    function _generate_summary_table($data)
    {
        $this->load->library('table');

        $colspan2 = array('colspan' => 2);

        $tmpl = array ( 'table_open'  => '<table class="table table-bordered table-hover table-condensed summary-stats">' );
        $this->table->set_template($tmpl);

        // table header
        $this->table->set_heading('', 'Molecule 1', 'Molecule 2');

        // Number of nucleotides
        $this->table->add_row('Number of nucleotides', $data['num_nt1'], $data['num_nt2']);

        // Number of aligned nucleotides
        $colspan2['data'] = $data['num_nt_aligned'];
        $this->table->add_row('Number of aligned nucleotides', $colspan2);

        // Percentage of aligned nucleotides
        $this->table->add_row('Percentage of aligned nucleotides',
                              number_format($data['perc_nt_aligned1'], 1) . '%',
                              number_format($data['perc_nt_aligned2'], 1) . '%');

        // Number of basepairs
        $this->table->add_row('Number of basepairs', $data['num_bp1'], $data['num_bp2']);

        // Number of aligned basepairs
        $colspan2['data'] = $data['num_bp_aligned'];
        $this->table->add_row('Number of aligned basepairs', $colspan2);

        // Percentage of aligned basepairs
        $this->table->add_row('Percentage of aligned basepairs',
                              number_format($data['perc_bp_aligned1'], 1) . '%',
                              number_format($data['perc_bp_aligned2'], 1) . '%');

        // Local discrepancy
        $colspan2['data'] = number_format($data['local_disc'], 2) . ' &Aring/nucleotide';
        $this->table->add_row('Mean local discrepancy', $colspan2);

        // Global discrepancy
        $colspan2['data'] = number_format($data['global_disc'], 2) . ' &Aring/nucleotide';
        $this->table->add_row('Global discrepancy', $colspan2);

        return $this->table->generate();
    }

}

/* End of file results_model.php */
/* Location: ./application/model/results_model.php */