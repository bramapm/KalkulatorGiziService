<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	var $method; 

	public function __construct()
	{
		parent::__construct();
	}

	public function _check_func($o)
    {
        if (is_object($o)) {
            $m = $this->post("method");
            if (method_exists($o, $m)) {
                $this->method = $m;
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function post($param = "")
    {
        if ($param != "") {
            return $this->input->post($param); //return string
        }else{
            return $this->input->post(); //return array
        }
    }

    public function get($param = "")
    {
        if ($param != "") {
            return $this->input->get($param); //return string
        }else{
            return $this->input->get(); //return array
        }
    }

    public function _api($code, $message, $data = array())
    {
        $api = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        echo json_encode($api);
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */