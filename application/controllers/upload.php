<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {


    public function upload_file()
    {
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $config['max_size']  = 1024 * 20;
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($file_element_name)) {
            $status = 'error';
            $msg = $this->upload->display_errors('', '');
        } else {
            $data = $this->upload->data();
            $status = "success";
            $msg = "File successfully uploaded";
        }
        @unlink($_FILES[$file_element_name]);

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

}

/* End of file upload.php */
/* Location: ./application/controllers/upload.php */