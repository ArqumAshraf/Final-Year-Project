<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Model {
	 function user_login($user_email, $user_pass){
		$grouped = '';
		$pass = md5($user_pass);
		$query = $this->db->where(['user_email'=>$user_email,'user_pass'=>$pass])
						  ->get('users');
		if($query->num_rows()){
			if($query->row()->status == 1){
				$grouped = array('type'=>'success','msg'=>'Login Successfully','id'=>$query->row()->id,'role'=>$query->row()->role,'name'=>$query->row()->full_name);
			}else
			if($query->row()->status == 0){
				$grouped = array('type'=>'failed','msg'=>'Your Account is Blocked');
			}//check status	
		}else{
			$grouped = array('type'=>'failed','msg'=>'Invalid Email/Password');
		}//check user exist
		return $grouped;
	}//end admin_login
	
	function view_profile($id){
	 $q = $this->db->select("*")
				->where('id',$id)
				->get('users');
		return $q->row();
	}//view_profile

	function update_profile($data, $id){
	 return $this->db
			->where('id',$id)
			->update('users', $data);
	}//edit_profile

	function create_user($data){
		if($this->db->insert('users',$data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}//create_user

	public function view_all_user(){
	 $q = $this->db->select("*")
	 			->order_by('id','desc')
				->get('users');
		return $q->result();
	}//view_all_user
}