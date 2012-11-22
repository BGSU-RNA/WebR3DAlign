<?php
class Results_model extends CI_Model {

    function __construct()
    {
        $CI = & get_instance();
        parent::__construct();
    }

    function get_basepair_comparison($query_id)
    {
        $filename = "/Servers/rna.bgsu.edu/r3dalign_dev/data/spreadsheets/{$query_id}/{$query_id}.csv";
        $table = '';
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $table .= '<tr>';
                for ($i = 0; $i < $num; $i++) {
                    if ( $i == 1 or $i == 4 ) {
                        $table .= "<td class='{$data[$i]}'>" . $data[$i] . '</td>';
                    } else {
                        $table .= '<td>' . $data[$i] . '</td>';
                    }
                }
                $table .= '</tr>';
            }
            fclose($handle);
        }
        return $table;
    }

    function get_alignment($query_id)
    {
        $filename = "/Servers/rna.bgsu.edu/r3dalign_dev/data/fasta/{$query_id}.fasta";
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