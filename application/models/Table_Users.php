<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_Users extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table = "user";
        $this->pri_index = "id_user";
        $this->format_pk = "";
	}

}

/* End of file Users.php */
/* Location: ./application/models/Users.php */