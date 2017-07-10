<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Makanan extends MY_Controller {
	
	function __construct() {
        parent::__construct();
        $this->load->model("Table_Makanan", "mkn");
    }

	public function index()
	{
		if($this->_check_func($this)){
			$m = $this->method;
			$this->$m();
		}else{
			$this->_api(JSON_ERROR, "No Method ".$this->method." in Class Makanan");
		}
	}

	public function get_makanan()
	{
		$makanan_code =   $this->post('id_makanan');
		if ($makanan_code != "") {
            $makanan = $this->mkn->get($makanan_code);
        }else{
            $makanan = $this->mkn->get();
        }
        $res = array();
        foreach ($makanan as $key) {
            $res[] = array( 
                "id_makanan"       	=> $key->id_makanan,
                "nama_makanan" 		=> $key->nama_makanan,
                "jenis"  			=> $key->jenis,
                "kkal" 				=> $key->kkal,
                "karbo"  			=> $key->karbo,
                "protein" 			=> $key->protein,
                "lemak" 			=> $key->lemak,
                "keterangan"        => $key->keterangan,
                "foto" 				=> base_url().'assets/upload/Makanan/'.str_replace(" ", "_", $key->nama_makanan).'.png'
            );
        }        
        $this->_api(JSON_SUCCESS, "Success Get Data Makanan", $res);
	}

    public function insert(){        
        $nm = $this->post('nama_makanan');
        $config = array();
        $config['max_size'] = '3072';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['overwrite']     = TRUE; 
        $config['upload_path']   = './assets/upload/Makanan/';
        $config['file_name']     = $nm.'.png';
        if (!file_exists($config["upload_path"])) {
            mkdir($config["upload_path"]);
        }
        $this->load->library('upload');
        $this->upload->initialize($config);
        $data = array(
            'id_makanan'        => $this->post('id_makanan'),
            'nama_makanan'      => $this->post('nama_makanan'),
            'jenis'             => $this->post('jenis'),
            'kkal'              => $this->post('kkal'),
            'karbo'             => $this->post('karbo'),
            'protein'           => $this->post('protein'),
            'lemak'             => $this->post('lemak'),            
            'keterangan'        => $this->post('keterangan'),
        );
        $where1 = $this->mkn->count(array('nama_makanan' => $this->post('nama_makanan')));
        if ($where1 > 0) {
            $this->_api(JSON_ERROR, "Data ".$nm." Telah Tersedia", $data['nama_makanan']);
        }else{
            $insert = $this->mkn->insert($data);
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
        $nm = $this->post('nama_makanan');

        $lokasi   = './assets/upload/Makanan/';

        $nama = $this->mkn->get($this->post("id_makanan"));
        $flold = "";
        if(isset($nama[0])){
            $flold = $lokasi.$nama[0]->nama_makanan.'.png';
        }
        $flnew = $lokasi.$nm.'.png';

        $data = array(                        
            'nama_makanan'      => $this->post('nama_makanan'),
            'jenis'             => $this->post('jenis'),
            'kkal'              => $this->post('kkal'),
            'karbo'             => $this->post('karbo'),
            'protein'           => $this->post('protein'),
            'lemak'             => $this->post('lemak'),            
            'keterangan'        => $this->post('keterangan'),
        );

        $update = $this->mkn->update($data, $this->post("id_makanan"));
        if ($update) {
            $where1 = $this->mkn->count(array('nama_makanan' => $this->post('nama_makanan')));
                if ($where1 > 0) {
                $this->_api(JSON_ERROR, "Data ".$nm." Telah Tersedia");
                } else {                    
                    if(file_exists($flold) && !empty($flold)){
                        rename($flold, $flnew);
                    }
                        if (isset($_FILES["foto"]) && $_FILES["foto"] != NULL) {
                            $config = array();
                            $config['max_size'] = '3072';
                            $config['allowed_types'] = 'jpeg|jpg|png';
                            $config['overwrite']     = TRUE; 
                            $config['upload_path']   = './assets/upload/Makanan/';
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
                    }
                } else {
            $this->_api(JSON_ERROR, "Update Data Gagal");
            }
        }

    public function delete(){
        $delete = $this->mkn->delete($this->post("id_makanan"));
        if ($delete) {
            $this->_api(JSON_SUCCESS, "Success Delete Data");
        } else {
            $this->_api(JSON_ERROR, "Delete Data Gagal");
        }
    }

    public function get_count(){    
        $lokasi   = './assets/upload/Makanan/';
        $oldTable = $this->mkn->get($this->post("id_makanan"));
        $delete = $this->mkn->delete($this->post("id_makanan"));
        if ($delete) {
            if(isset($oldTable[0])){
                $fl = $lokasi.$oldTable[0]->nama_makanan.'.png';
                if (file_exists($fl)) {
                    unlink($fl);
                }
            }
            $this->_api(JSON_SUCCESS, "Success get Data");
        } else {
            $this->_api(JSON_ERROR, "Delete Data Gagal");
        }
    }
}


/* End of file Makanan.php */
/* Location: ./application/controllers/Makanan.php */