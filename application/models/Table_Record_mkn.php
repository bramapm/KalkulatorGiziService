<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_Record_mkn extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->table = "record_mkn";
        $this->pri_index = "id_recordmkn";
        $this->format_pk = "";
    }    
}

/* End of file Table_Makanan.php */
/* Location: ./application/models/Table_Makanan.php */