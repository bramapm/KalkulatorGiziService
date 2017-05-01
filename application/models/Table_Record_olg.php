<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_Record_olg extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->table = "record_olg";
        $this->pri_index = "id_recordolg";
        $this->format_pk = "";
    }
}

/* End of file Table_Makanan.php */
/* Location: ./application/models/Table_Makanan.php */