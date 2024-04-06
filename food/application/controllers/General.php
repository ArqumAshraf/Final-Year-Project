<?php defined('BASEPATH') OR exit('No direct script access allowed');

class General extends MY_Controller {
	public $data = [];
	function __construct() {
		parent::__construct();
		$this->user_id = $this->session->userdata('id');
	}//end construct
	
	public function index(){
		redirect(base_url());
	}//index

    public function getNotification(){
        $response = ['status' => 0, 'msg' => '', 'order_type' => '', 'order_id' => ''];
        $latest_notification = $this->main->view('web_notification', [
            'user_id' => $this->user_id,
            'status'  => 0,
            'created_at >=' => date('Y-m-d H:i:s', strtotime('-20 minutes'))
        ], false, 'single');

        if(isset($latest_notification) && !empty($latest_notification)){
            $this->main->update('web_notification', $latest_notification->id, ['status' => 1]);

            $response['status']     = 1;
            $response['msg']        = $latest_notification->msg;
            $response['order_type'] = $latest_notification->type;
            $response['order_id']   = $latest_notification->order_id;
        }
        echo json_encode($response);
    } // getNotification
}