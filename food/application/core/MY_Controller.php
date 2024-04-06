<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    function __construct(){
        parent::__construct();

        $this->user_id = $this->session->userdata('id');
        $this->data['user_profile'] = $this->users->view_profile($this->user_id);

        $this->data['current_slug'] = 'Dashboard';
        $this->data['current_page'] = 'dashboard';

        $this->data['current_role'] = $this->session->userdata('role');

        $this->load->model('main_model', 'main');

		// Check if the "mobile" word exists in User-Agent 
		$isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")); 
		if($isMob){ 
			$this->data['current_device'] = 'mobile';
		}else{ 
			$this->data['current_device'] = 'desktop';
		}
    }

    public function _upload_file($name, $type, $tmp, $size, $img_path){
		if($type == 'image/jpeg' || $type == 'image/jpg' || $type == 'image/png'){
			$new_name = microtime().$name;
			$final_new_name = str_replace(" ","",$new_name);

			$path = './assets/'.$img_path.$final_new_name;
			if(move_uploaded_file($tmp, $path)){
				return $final_new_name;
			}//moveupload
		}//check img size
	}//_upload_file

    public function _signout(){
		$redirect_url = base_url("login");
		if($this->data['current_device'] == 'mobile' && $this->session->userdata('role') == 'rider'){
			$device_id_now = $this->session->userdata('device_id');
			$redirect_url = base_url("login?device_id=$device_id_now");
		}

		if($this->data['current_device'] == 'mobile' && $this->session->userdata('role') == 'rider'){
			$this->load->model('main_model', 'main');
			$this->main->delete('device', 'user_id', $this->session->userdata('id'));
			$this->main->delete('notification', 'user_id', $this->session->userdata('id'));
			$this->session->unset_userdata('device_id');		
		}
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('role');
		$this->session->unset_userdata('full_name');
		redirect($redirect_url);
	}//_signout
}