<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Olahraga extends MY_Controller {
	
	function __construct() {
        parent::__construct();
        $this->load->model("Table_Olahraga", "olg");
    }

	public function index()
	{
		if($this->_check_func($this)){
			$m = $this->method;
			$this->$m();
		}else{
			$this->_api(JSON_ERROR, "No Method ".$this->method." in Class olahraga");
		}
	}

	public function get_olahraga()
	{
		$olahraga_code =   $this->post('id_olahraga');
		if ($olahraga_code != "") {
            $olahraga = $this->olg->get($olahraga_code);
        }else{
            $olahraga = $this->olg->get();
        }
        $res = array();
        foreach ($olahraga as $key) {
            $res[] = array( 
                "id_olahraga"       => $key->id_olahraga,
                "nama_olahraga" 	=> $key->nama_olahraga,
                "kkal" 				=> $key->kkal,
                "keterangan"  		=> $key->keterangan,
                "foto" 				=> base_url().'assets/upload/Olahraga/'.$key->nama_olahraga.'.png'
            );
        }
        $this->_api(JSON_SUCCESS, "Success Get Data Olahraga", $res);
	}

    public function insert(){
        $nm = $this->post('nama_olahraga');
        $config = array();
        $config['max_size'] = '3072';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['overwrite']     = TRUE; 
        $config['upload_path']   = './assets/upload/Olahraga/';
        $config['file_name']     = $nm.'.png';
        if (!file_exists($config["upload_path"])) {
            mkdir($config["upload_path"]);
        }
        $this->load->library('upload');
        $this->upload->initialize($config);
        $data = array(
            'nama_olahraga'     => $this->post('nama_olahraga'),
            'kkal'              => $this->post('kkal'),
            'keterangan'        => $this->post('keterangan'),            
        );
        $where1 = $this->olg->count(array('nama_olahraga' => $this->post('nama_olahraga')));
        if ($where1 > 0) {
            $this->_api(JSON_ERROR, "Data Telah Tersedia");
        }else{
            $insert = $this->olg->insert($data);
            if ($insert) {
                //$this->_api(JSON_SUCCESS, "Success Insert Data", $data);
                if (isset($_FILES["foto"]) && $_FILES["foto"] != NULL) {
                    if (!$this->upload->do_upload("foto")) {
                        $this->_api(JSON_ERROR, "Insert Foto Gagal");
                        exit(0);
                    }
                }
                $this->_api(JSON_SUCCESS, "Success Insert Data", $data);
            } else {
                $this->_api(JSON_ERROR, "Insert Data Gagal");
            }
        }
    }

    public function update(){
        $nm = $this->post('nama_olahraga');

        $lokasi   = './assets/upload/Olahraga/';

        $nama = $this->olg->get($this->post("id_olahraga"));
        $flold = "";
        if(isset($nama[0])){
            $flold = $lokasi.$nama[0]->nama_olahraga.'.png';
        }
        $flnew = $lokasi.$nm.'.png';

        $data = array(            
            'nama_olahraga'     => $this->post('nama_olahraga'),
            'kkal'              => $this->post('kkal'),
            'keterangan'        => $this->post('keterangan'),          
        );

        $update = $this->olg->update($data, $this->post("id_olahraga"));
        if ($update) {
            if(file_exists($flold) && !empty($flold)){
                rename($flold, $flnew);
            }
            if (isset($_FILES["foto"]) && $_FILES["foto"] != NULL) {
                $config = array();
                $config['max_size'] = '3072';
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['overwrite']     = TRUE; 
                $config['upload_path']   = './assets/upload/Olahraga/';
                $config['file_name']     = $nm.'.png';
                if (!file_exists($config["upload_path"])) {
                    mkdir($config["upload_path"]);
                }
                $this->load->library('upload');
                $this->upload->initialize($config);

                if (!$this->upload->do_upload("foto")) {
                    $this->_api(JSON_ERROR, "Insert Foto Gagal");
                    exit(0);
                }
            }
            $this->_api(JSON_SUCCESS, "Success Update Data");
        } else {
            $this->_api(JSON_ERROR, "Update Data Gagal");
        }
    }

    public function delete(){
        $lokasi   = './assets/upload/Olahraga/';
        $oldTable = $this->olg->get($this->post("id_olahraga"));
        $delete = $this->olg->delete($this->post("id_olahraga"));
        if ($delete) {
            if(isset($oldTable[0])){
                $fl = $lokasi.$oldTable[0]->nama_olahraga.'.png';
                if (file_exists($fl)) {
                    unlink($fl);
                }
            }
            $this->_api(JSON_SUCCESS, "Success Delete Data");
        } else {
            $this->_api(JSON_ERROR, "Delete Data Gagal");
        }
    }
}

/* End of file olahraga.php */
/* Location: ./application/controllers/olahraga.php */