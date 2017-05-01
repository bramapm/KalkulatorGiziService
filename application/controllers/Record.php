<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Record extends MY_Controller {
	
	function __construct() {
        parent::__construct();
        $this->load->model("Table_Record_olg", "recordOlg");
        $this->load->model("Table_Record_mkn", "recordMkn");
    }

	public function index()
	{
		if($this->_check_func($this)){
			$m = $this->method;
			$this->$m();
		}else{
			$this->_api(JSON_ERROR, "tidak ada method ".$this->method." di class record");
		}
	}

	public function get_recordOlg()
	{
		$recordCode =   $this->post('id_recordolg');
		if ($recordCode != "") {
            $olahraga = $this->recordOlg->get($recordCode);
        }else{
            $olahraga = $this->recordOlg->get();
        }
        $res = array();
        foreach ($olahraga as $key) {
            $res[] = array( 
            	"id_recordolg"      => $key->id_recordolg,
                "id_olahraga"       => $key->id_olahraga,                
                "tanggal"	  		=> $key->tanggal,
                "kalori"			=> $key->kalori,               
            );
        }
        $this->_api(JSON_SUCCESS, "Success Get Data Record Olahraga", $res);
	}

    public function insertOlg(){
        $data = array(
        	//'id_recordolg'		=> $this->post('id_recordolg'),
            'id_user'			=> $this->post('id_user'),
            'id_olahraga'		=> $this->post('id_olahraga'),                       
            'tanggal'			=> $this->post('tanggal'),
            'kalori'			=> $this->post('kalori'),            
        );
        $insert = $this->recordOlg->insert($data);
        if ($insert) {
        	$this->_api(JSON_SUCCESS, "Success Insert Data", $data);                        
        } else {
            $this->_api(JSON_ERROR, "Insert Data Gagal");
       	}
    }

    public function insertMkn(){
        $data = array(
        	//'id_recordolg'		=> $this->post('id_recordolg'),
            'id_user'			=> $this->post('id_user'),
            'id_makanan'		=> $this->post('id_makanan'),
            'kat_waktu'			=> $this->post('kat_waktu'),
            'tanggal'			=> $this->post('tanggal'),
            'kalori'			=> $this->post('kalori'),
        );
        $insert = $this->recordMkn->insert($data);
        if ($insert) {
        	$this->_api(JSON_SUCCESS, "Success Insert Data", $data);                        
        } else {
            $this->_api(JSON_ERROR, "Insert Data Gagal");
      	}
    }

    public function getCountKaloriMkn(){
    	$id 	= $this->post('id_user');
    	$query = "SELECT SUM(kalori) FROM `record_olg` WHERE id_user=0";
    	$res = $query->result();
    	return $res;
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