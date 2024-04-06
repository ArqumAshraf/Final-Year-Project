<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	public $data = [];
	function __construct() {
		parent::__construct();
		if(!$this->session->userdata('id') || $this->session->userdata('role') != 'admin'){
			return redirect(base_url('login'));
		}
		$this->data["current_user_id"] = $this->user_id = $this->session->userdata('id');
	}//end construct
	
	public function index(){
		redirect(base_url('admin/profile'));
	}//index

	public function dashboard(){
		$this->load->view('dashboard', $this->data);
	}//index
	
	public function profile(){
		$this->data['current_slug'] = 'My Profile';

		if($_POST){
			if($this->form_validation->run('profile') == FALSE){
				$this->data['user_profile'] = $this->users->view_profile($this->user_id);
				$this->load->view('profile', $this->data);
			}else{
				$post 	   = $this->input->post();
				$user_pass = $this->input->post('user_pass');
				$img_id    = $this->input->post('img_id');
				
				if(!empty($user_pass)){
					if(strlen($user_pass) >= 6){
						$post['user_pass'] = md5($this->input->post('user_pass'));
					}else{
						$this->session->set_flashdata('error', 'Password must be graterthan 5 digits');
						redirect(base_url('admin/profile'));
					}
				}else{
					if(isset($_POST['user_pass'])){
						unset($post['user_pass']);
					}
				}
				$profile_pic = $_FILES['profile_pic'];
					
				if(!empty($profile_pic['name'])){
					$img_path = 'profile/admin/';
					if($new_name = $this-> _upload_file($profile_pic['name'], $profile_pic['type'], $profile_pic['tmp_name'], $profile_pic['size'], $img_path)){
						$post['profile_pic'] = $new_name;
						unlink('./assets/'.$img_path.$img_id);
					}else{
						$post['profile_pic'] = $img_id;	
					}
					unset($post['img_id']);
				}else{
					$post['profile_pic'] = $img_id;
					unset($post['img_id']);
				}

				if($this->users->update_profile($post, $this->user_id)){
					$this->session->set_flashdata('feedback',"Profile Updated Successfully.");
					redirect(base_url('admin/profile'));
				}else{
					$this->session->set_flashdata('error',"Profile Not Update. Plz Try Again!.");
					redirect(base_url('admin/profile'));
				}//model
			}//validation
		}else{
			$this->data['user_profile'] = $this->users->view_profile($this->user_id);
			$this->load->view('profile', $this->data);
		}//post
	}//profile

	public function users($action="", $id=0){
		if($action=="add"){
			if($_POST){
				if($this->form_validation->run('create_user') == FALSE){
					$this->session->set_flashdata('error',validation_errors());
					redirect(base_url('admin/users/add'));		
				}else{
					$post = $this->input->post();
					$post['user_pass'] = md5($post['user_pass']);
					
					if($this->users->create_user($post)){
						$this->session->set_flashdata('feedback',"Create User Successfully.");
						redirect(base_url('admin/users/manage'));
					}else{
						$this->session->set_flashdata('error',"User Not Create. Plz Try Again!.");
						redirect(base_url('admin/users/manage'));
					}//end if else model
				}//end else valid	
			}else{
				$this->data['current_slug'] = 'Add User';
				$this->data['current_page'] = 'add_user';
				$this->load->view('admin/users/add', $this->data);
			}//end else post
		}//add
		
		if($action=="edit"){
			if($_POST){
				$post = $this->input->post();	
				unset($post['user_pass']);
				$user_pass = $this->input->post('user_pass');

				if(!empty($user_pass)){
					if(strlen($user_pass) >= 6){
						$post['user_pass'] = md5($user_pass);
					}else{
						$this->session->set_flashdata('error', 'Password must be graterthan 5 digits');
						redirect(base_url('admin/users/edit'));
					}
				}

				if($this->users->update_profile($post, $id)){
					$this->session->set_flashdata('feedback',"User Updated Successfully.");
					redirect(base_url('admin/users/manage'));
				}else{
					$this->session->set_flashdata('error',"User Not Update. Plz Try Again!.");
					redirect(base_url('admin/users/manage'));
				}//end if else model
			}else{
				$this->data['rs_view'] = $this->users->view_profile($id);
				$this->data['current_slug'] = 'Edit User';
				$this->data['current_page'] = 'users';
				$this->load->view('admin/users/edit', $this->data);
			}//end else post
		}//edit
		
		if($action=="manage"){
			$this->data['user_id']   = $this->user_id;
			$this->data['rs_manage'] = $this->users->view_all_user();
			$this->data['current_page'] = 'user_list';
			$this->data['current_slug'] = 'User List';
			$this->load->view('admin/users/manage', $this->data);
		}//manage
	}//users

	public function signout(){
		$this->_signout();
	}//signout
}