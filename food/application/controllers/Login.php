<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		if($this->session->userdata('id')){
			return redirect(base_url($this->session->userdata('role')));
		}
		// Check if the "mobile" word exists in User-Agent 
		$isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")); 
		if($isMob){ 
			$this->data['current_device'] = 'mobile';
		}else{ 
			$this->data['current_device'] = 'desktop';
		}
		$this->load->model('main_model', 'main');
	}//end construct
	
	public function index(){
		$this->load->view("login", $this->data);
	}//end index	
	
	public function signin(){
		extract($_POST);
		if($_POST){
			if($this->form_validation->run('login') == FALSE){
				$this->load->view("login", $this->data);
			}else{
				if($user_email){
					$user_data = $this->users->user_login($user_email, $user_pass);
					if($user_data['type'] == 'failed'){
						$this->session->set_flashdata('Login_Failed',$user_data['msg']);
						$this->load->view("login", $this->data);
					}else if($user_data['type'] == 'success'){
						if($this->data['current_device'] == 'mobile' && $user_data['role'] != 'rider'){
							$this->session->set_flashdata('Login_Failed', 'You have not allow to login on mobile');
							$this->load->view("login", $this->data);
						}else{
							if($this->data['current_device'] == 'mobile' && $user_data['role'] == 'rider'){
								if(isset($device_id) && !empty($device_id)){
									if($this->main->exist('device', ['device_id' => $device_id])){
										$this->main->delete('device', 'device_id', $device_id);
										$this->main->delete('notification', 'device_id', $device_id);
										$this->db->insert('device', [
											'device_id' => $device_id,
											'user_id'   => $user_data['id']
										]);
									}else{
										$this->db->insert('device', [
											'device_id' => $device_id,
											'user_id'   => $user_data['id']
										]);
									}
									$this->session->set_userdata('device_id', $device_id);
								}else{
									$this->session->set_flashdata('Login_Failed', 'Device id not available');
									$this->load->view("login", $this->data);
								}
							}
						}
						$this->session->set_userdata('id', $user_data['id']);
						$this->session->set_userdata("full_name", $user_data['name']);
						$this->session->set_userdata("role", $user_data['role']);
						return redirect(base_url($user_data['role']));
					}//success
				}//check user
			}//validation else
		}else{
			$this->load->view("login", $this->data);
		}//post else
	}
}