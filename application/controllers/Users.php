<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model("Table_Users", "usr");
    }
	public function index()
	{
		if($this->_check_func($this)){
			$m = $this->method;
			$this->$m();
		}else{
			$this->_api(JSON_ERROR, "No Method ".$this->method." in Class Users");
		}
	}
	public function login()
	{
		$users = NULL;
        $username = $this->post("username");
        $password = $this->post("password");
        if ($username != "" && $password != "") {
            $data = array(
                "username"=>$username,
                "password"=>$password
            );
            $users = $this->usr->get($data);
            if (!$users) {
                $data = array(
                    "username"=>$username,
                    "password"=>$password
                );
                $users = $this->usr->get($data);
            }
        }
        if ($users) {
            $this->_api(JSON_SUCCESS, "Success Login", $users);
        }else{
            $this->_api(JSON_ERROR, "Failed Login");
        }
	}
	public function get_data()
	{
		$users_code =   $this->post('ucode');
		if ($users_code != "") {
            $users = $this->users->get($users_code);
        }else{
            $users = $this->users->get();
        }
        $this->_api(JSON_SUCCESS, "Success Get Data", $users);
	}

	public function get_feed()
	{
		$users_code =   $this->post('ucode');
		if ($users_code != "") {
            $users = $this->users->rawQuery('call get_feed("'.$users_code.'")')->result();
        }else{
            $users = $this->users->rawQuery('call get_feed_all()')->result();
        }
        $res = array();
        foreach ($users as $key) {
            $res[] = array( 
                "users_code_main"       => $key->users_code_main,
                "users_full_name_main" => $key->users_full_name_main,
                "username_main"  => $key->username_main,
                "users_code_follower"  => $key->users_code_follower,
                "username_follower"  => $key->username_follower,
                "users_full_name_follower" => $key->users_full_name_follower,
                "photo_code" => $key->photo_code,
                "photo_description" => $key->photo_description,
                "photo_map_lat" => $key->photo_map_lat,
                "photo_map_lon" => $key->photo_map_lon,
                "photo_date_modified" => $key->photo_date_modified,
                "photo_url" => base_url("users/upload/".$key->users_code_follower."/".$key->photo_code.".png"),
                "users_photo_follower" => base_url("users/profile/pp_".$key->users_code_main.".png")
            );
        }
        // base_url("upload/users/".$key->users_code_follower."/".$key->photo_code.".png")
        $this->_api(JSON_SUCCESS, "Success Get Feed", $res);
	}

	public function register()
	{
		$data = array(
            'id_user'                               => $this->usr->getNewIndex(),
            'username'                              => $this->post('username'),
            'password'                              => $this->post('password'),
            'email'                                 => $this->post('email'),
            'nama_user'                             => $this->post('nama_user'),
            'status'                                => "member"
        );
        $where1 = $this->usr->count(array('email' => $this->post('email')));
        $where2 = $this->usr->count(array('username' => $this->post('username')));
        if ($where1 > 0 || $where2 > 0) {
            $this->_api(JSON_ERROR, "Username / Email Telah Tersedia");
        }else{
            $insert = $this->usr->insert($data);
            if ($insert) {
                $this->_api(JSON_SUCCESS, "Success Registration", $data);
            } else {
                $this->_api(JSON_ERROR, "Failed Registration");
            }
        }
	}

	public function update_akun()
	{
		$users_code =   $this->post('id_user');
        if ($users_code) {

        $data = array(
            'username'  => $this->post('username'),
            'password'  => $this->post('password'),       
            'email'     => $this->post('email'),
            'nama_user' => $this->post('nama_user'),
            'jk'        => $this->post('jk'),
            'ttl'       => $this->post('ttl'),
            'tinggi'    => $this->post('tinggi'),
            'berat'     => $this->post('berat'),
            'umur'      => $this->post('umur'),
            'kalori'    => $this->post('kalori')
        );

            if ($data != NULL) {
                $update = $this->usr->update($data, $users_code);
                if ($update) {
        			$this->_api(JSON_SUCCESS, "Success Update");
                } else {
            		$this->_api(JSON_ERROR, "Failed Update 1, check your input data");
                }
            } else {
        		$this->_api(JSON_ERROR, "Failed Update 2, because data null");
                }
	       } else {
        $this->_api(JSON_ERROR, "Failed Update 3, in where clause");
       }
    }

public function update_profile()
    {
        $users_code =   $this->post('ucode');
        if ($users_code) {
            $data = NULL;
            if ($this->post('username') != "") {
                $data["users_login_username"] = $this->post('username');
            }
            if ($this->post('password') != "") {
                $data["users_login_password"] = $this->post('password');
            }
            if ($this->post('email') != "") {
                $data["users_email"] = $this->post('email');
            }
            if ($this->post('first_name') != "") {
                $data["users_first_name"] = $this->post('first_name');
            }
            if ($this->post('mid_name') != "") {
                $data["users_mid_name"] = $this->post('mid_name');
            }
            if ($this->post('last_name') != "") {
                $data["users_last_name"] = $this->post('last_name');
            }
            if ($this->post('gender') != "") {
                $data["users_gender"] = $this->post('gender');
            }
            if ($this->post('date_of_birth') != "") {
                $data["users_date_of_birth"] = $this->post('birth');
            }
            if ($this->post('website') != "") {
                $data["users_website"] = $this->post('website');
            }
            if ($this->post('bio') != "") {
                $data["users_bio"] = $this->post('bio');
            }
            if ($this->post('phone') != "") {
                $data["users_phone"] = $this->post('phone');
            }
            if ($data != NULL) {
                $update = $this->users->update($data, $users_code);
                if ($update) {
                    $this->_api(JSON_SUCCESS, "Success Update");
                } else {
                    $this->_api(JSON_ERROR, "Failed Update");
                }
            } else {
                $this->_api(JSON_ERROR, "Failed Update");
            }
        }else{
            $this->_api(JSON_ERROR, "Failed Update");
        }
    }

	public function delete()
	{
		$users_code = $this->post('ucode');
        if ($users_code != "") {
            $delete = $this->users->post($users_code);
            if ($delete) {
        			$this->_api(JSON_SUCCESS, "Success Delete");
            } else {
        		$this->_api(JSON_ERROR, "Failed Delete");
            }
        } else {
    		$this->_api(JSON_ERROR, "Failed Delete");
        }
	}
}
