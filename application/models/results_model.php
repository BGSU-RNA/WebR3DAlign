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


}

/* End of file results_model.php */
/* Location: ./application/model/results_model.php */