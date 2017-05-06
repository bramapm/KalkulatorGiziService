<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Record extends MY_Controller {
	
	function __construct() {
        parent::__construct();
        $this->load->model("Table_Record_olg", "recordOlg");
        $this->load->model("Table_Record_mkn", "recordMkn");
        $this->load->model("Table_Makanan", "mkn");
        $this->load->model("Table_Olahraga", "olg");
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
                "id_user"           => $key->id_user,
                "tanggal"	  		=> $key->tanggal,
                "kalori"			=> $key->kalori,               
            );
        }
        $this->_api(JSON_SUCCESS, "Success Get Data Record Olahraga", $res);
	}

    public function get_recordMkn()
    {
        $recordCode =   $this->post('id_recordmkn');
        if ($recordCode != "") {
            $olahraga = $this->recordMkn->get($recordCode);
        }else{
            $olahraga = $this->recordMkn->get();
        }
        $res = array();
        foreach ($olahraga as $key) {
            $res[] = array( 
                "id_recordmkn"      => $key->id_recordmkn,
                "id_makanan"        => $key->id_makanan,
                "id_user"           => $key->id_user,
                "tanggal"           => $key->tanggal,
                "kat_waktu"         => $key->kat_waktu,
                "kalori"            => $key->kalori,               
            );
        }
        $this->_api(JSON_SUCCESS, "Success Get Data Record Olahraga", $res);
    }

    public function insertOlg(){
        $kal = $this->mkn->get($this->post('id_olahraga'), 'kkal');
        $data = array(
        	//'id_recordolg'		=> $this->post('id_recordolg'),
            'id_user'			=> $this->post('id_user'),
            'id_olahraga'		=> $this->post('id_olahraga'),                       
            'tanggal'			=> $this->post('tanggal'),
            'kalori'			=> (is_object($kal[0])) ? $kal[0]->kkal : 0
        );
        $insert = $this->recordOlg->insert($data);
        if ($insert) {
        	$this->_api(JSON_SUCCESS, "Success Insert Data", $data);                        
        } else {
            $this->_api(JSON_ERROR, "Insert Data Gagal");
       	}
    }

    public function insertMkn(){
        $kal = $this->mkn->get($this->post('id_makanan'), 'kkal');
        $data = array(
        	//'id_recordmkn'	=> $this->post('id_recordmkn'),
            'id_user'			=> $this->post('id_user'),
            'id_makanan'		=> $this->post('id_makanan'),
            'kat_waktu'			=> $this->post('kat_waktu'),
            'tanggal'			=> $this->post('tanggal'),
            'kalori'			=> (is_object($kal[0])) ? $kal[0]->kkal : 0
        );
        $insert = $this->recordMkn->insert($data);
        if ($insert) {
        	$this->_api(JSON_SUCCESS, "Success Insert Data", $data);
        } else {
            $this->_api(JSON_ERROR, "Insert Data Gagal");
      	}
    }

    public function countKaloriMkn(){
        // 'kat_waktu' => $this->post('kat_waktu'),
        $kal = array(0,0,0,0);
        for ($i=0; $i < 4; $i++) { 
            $waktu = "pagi";
            switch ($i) {
                case 1:
                    $waktu = "siang";
                    break;
                case 2:
                    $waktu = "malam";
                    break;
                case 3:
                    $waktu = "lain";
                    break;
                default:
                    $waktu = "pagi";
                    break;
            }
            $query  = $this->recordMkn->sum('kalori', array(
                'id_user' => $this->post('id_user'),
                'tanggal' => $this->post('tanggal'),
                'kat_waktu' => $waktu,
            ));
            if (isset($query[0])) {
                if (is_null($query[0]->kalori)) {
                    $kal[$i] = 0;
                }else{
                    $kal[$i] = $query[0]->kalori;
                }
            }
        }
        $this->_api(JSON_SUCCESS, "Success Count Data", $kal);
    }

    public function countKaloriMknTotal(){
        $query  = $this->recordMkn->sum('kalori', array(
            'id_user' => $this->post('id_user'),
            'tanggal' => $this->post('tanggal'),                        
            ));
        if ($query) {
            $this->_api(JSON_SUCCESS, "Success Count Data", $query);
        } else {
            $this->_api(JSON_ERROR, "Failed Count Data");
        }
    }

    public function update(){
        
    }

    public function saran(){
        //$data =   $this->post('');
        // $kal = 500;
        // $num = "<= ".$kal;
        // $con = array('kkal'     => $num);
        // $data = $this->olg->get()->where('kkal', array());
        
        // $this->_api(JSON_SUCCESS, "Success Get Data", $data);
    }

    public function delete(){
    }
}

/* End of file olahraga.php */
/* Location: ./application/controllers/olahraga.php */