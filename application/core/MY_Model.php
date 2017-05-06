<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public $table = "";
    public $pri_index = "";
    public $format_pk = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_object($condition = NULL, $select = NULL)
    {
        if ($select !== NULL) {
            $this->db->select($select);
        }
        
        if ($condition !== NULL) {
            if (is_array($condition)) {
                $this->db->where($condition);
            } else {
                $this->db->where($this->pri_index, $condition);
            }
        }
        $result = $this->db->get($this->table);
        return $result;
    }
    public function get($condition = NULL, $select = NULL){
        $result = $this->get_object($condition, $select)->result();
        return $result;
    }
    public function insert($data){
        $result = $this->db->insert($this->table, $data);
        return $result;
    }
    public function update($data, $condition){
        if (is_array($condition)) {
            $this->db->where($condition);
        } else {
            $this->db->where($this->pri_index, $condition);
        }
        $result = $this->db->update($this->table, $data);
        return $result;
    }
    public function delete($condition){
        if (is_array($condition)) {
            $this->db->where($condition);
        } else {
            $this->db->where($this->pri_index, $condition);
        }
        $result = $this->db->delete($this->table);
        return $result;
    }
    public function count($condition = NULL, $select = NULL){
        $result = $this->get_object($condition, $select)->num_rows();
        return $result;
    }
    public function sum($select_sum, $condition = NULL){
        $this->db->select_sum($select_sum);
        $result = $this->get($condition);
        return $result;
    }

    public function sum_day($select_sum, $condition = NULL, $condition2 = NULL){
        $this->db->select_sum($select_sum);
        $result = $this->get($condition);
        $result = $this->get($condition2);
        return $result;
    }

    public function saran($kal = NULL, $table = NULL){        
        $this->db->where("kkal <", $kal);
        $result = $this->db->get($table);
        return $result->result();
    }

    public function rawQuery($query){
        $result = $this->db->query($query);
        return $result;
    }
    
    public function getNewIndex()
    {
        $this->db->limit(1);
        $this->db->select($this->pri_index." as pri_index");
        $this->db->from($this->table);
        $this->db->order_by($this->pri_index, "DESC");
        $q = $this->db->get();
        $fix_pk = "";
        $id = 1;
        if ($q->num_rows() > 0) {
            $q = $q->row();
            $id = intval(substr($q->pri_index, strlen($this->format_pk)));
            $id += 1;
            if ($id < 10) {
                $fix_pk = $this->format_pk."0000000".$id;
            }elseif ($id < 100) {
                $fix_pk = $this->format_pk."000000".$id;
            }elseif ($id < 1000) {
                $fix_pk = $this->format_pk."00000".$id;
            }elseif ($id < 10000) {
                $fix_pk = $this->format_pk."0000".$id;
            }elseif ($id < 100000) {
                $fix_pk = $this->format_pk."000".$id;
            }elseif ($id < 1000000) {
                $fix_pk = $this->format_pk."00".$id;
            }elseif ($id < 10000000) {
                $fix_pk = $this->format_pk."0".$id;
            }elseif ($id < 100000000) {
                $fix_pk = $this->format_pk."".$id;
            }
        }else{
            $fix_pk = $this->format_pk."0000000".$id;
        }
        return $fix_pk;
    }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */