<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ngo extends MY_Controller {
	public $data = [];
	function __construct() {
		parent::__construct();
		if(!$this->session->userdata('id') || $this->session->userdata('role') != 'ngo'){
			return redirect(base_url('login'));
		}
		$this->data["current_user_id"] = $this->user_id = $this->session->userdata('id');
	}//end construct
	
	public function index(){
		redirect(base_url('ngo/profile'));
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
						redirect(base_url('ngo/profile'));
					}
				}else{
					if(isset($_POST['user_pass'])){
						unset($post['user_pass']);
					}
				}
				$profile_pic = $_FILES['profile_pic'];
					
				if(!empty($profile_pic['name'])){
					$img_path = 'profile/ngo/';
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
					redirect(base_url('ngo/profile'));
				}else{
					$this->session->set_flashdata('error',"Profile Not Update. Plz Try Again!.");
					redirect(base_url('ngo/profile'));
				}//model
			}//validation
		}else{
			$this->data['user_profile'] = $this->users->view_profile($this->user_id);
			$this->load->view('profile', $this->data);
		}//post
	}//profile

	public function itemStock(){
		$this->data['user_id']  = $this->user_id;
		$this->data['rs_stock'] = $this->main->getStock($this->user_id);
		$this->data['current_slug'] = 'Item Stock';
		$this->data['current_page'] = 'ngo_item_stock';
		$this->load->view('food_item/stock', $this->data);
	} //itemStock

	public function requestOrder(){
		if($_POST){
			$post = $this->input->post();

			if($new_order_id = $this->main->addData('orders', 
				[
					'ngo_id' 		=> $this->user_id,
					'restaurant_id' => $post['restaurant_id'],
					'order_type' 	=> 'request',
					'created_at' 	=> date('Y-m-d H:i:s')
				]
			)){
				foreach($post['item_id'] as $key => $value){
					if($value > 0){
						if(isset($post['item_qty'][$key]) && $post['item_qty'][$key] >= 1){
							$rqty = $post['item_qty'][$key];
						}else{
							$rqty = 1;
						}
						$item_data = [
							'order_id'    => $new_order_id,
							'item_id' 	  => $value,
							'request_qty' => $rqty
						];
						$this->main->addData('order_details', $item_data);
					}
				}

				$this->main->addData('web_notification', [
					'user_id' 	 => $post['restaurant_id'],
					'order_id' 	 => $new_order_id,
					'type' 		 => 'request',
					'msg'		 => 'You receive new order, If you want to see, plz click on ok button.',
					'created_at' => date('Y-m-d H:i:s')
				]);

				$this->session->set_flashdata('feedback',"Request order create successfully.");
				redirect(base_url('panel/requestOrder'));
			}else{
				$this->session->set_flashdata('error',"Request order not create plz try again later!");
				redirect(base_url('ngo/requestOrder'));
			}
		}else{
			$this->data['current_slug'] = 'Request New Order';
			$this->data['current_page'] = 'orders';
			$this->data['rs_foods'] = $this->main->view('food_item', false, ['item_name', 'asc']);
			$this->data['rs_restaurants'] = $this->main->view('users', ['status' => 1, 'role' => 'restaurant'], ['full_name', 'asc']);
			$this->load->view('orders/add_request', $this->data);
		}
	} // requestOrder

	public function editRequestOrder($order_id){
		$request_order = $this->main->getRequestOrder($order_id);
		if(isset($request_order) && $request_order){
			$this->data['rs_order'] 	= $request_order;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'orders';

			$this->data['order_status'] = $request_order[0]->status;

			$this->data['order_id'] = $order_id;

			$this->data['restaurant_name'] = $this->main->view('users', ['id' => $request_order[0]->restaurant_id], false, 'single', 1, 'full_name');
			$this->data['ngo_name'] 	   = $this->main->view('users', ['id' => $request_order[0]->ngo_id], false, 'single', 1, 'full_name');

			$all_riders = $this->main->view('users', ['role' => 'rider', 'status' => 1, 'available' => 1]);

			$available_riders = [];
			foreach($all_riders as $rec_rider){
				$rider_exist = $this->main->exist('rider_current_order', ['rider_id' => $rec_rider->id]);
				if(!$rider_exist){
					$available_riders[] = $rec_rider;
				}
			}
			$this->data['all_riders'] = $available_riders;

			if(!empty($request_order[0]->rider_id)){
				$this->data['rider_name'] = $this->main->view('users', ['id' => $request_order[0]->rider_id], false, 'single', 1, 'full_name');
			}else{
				$this->data['rider_name'] = '';
			}
			$this->load->view('orders/edit_ngo_request_order', $this->data);
		}else{
			$this->session->set_flashdata('error',"Invalid Order");
			redirect(base_url('panel/requestOrder'));
		}
	} // editRequestOrder

	public function finalRequestOrder(){
		if($_POST){
			$post = $this->input->post();
			
			$order_id = $post['order_id'];
			$order_last_details = $this->main->view('orders', ["id" => $post['order_id']], false, 'single');

			unset($post['order_id']);

			$msg = "Order is updated now, If you want to see, plz click on ok button.";	

			if(isset($post['rider_id']) && $post['rider_id'] > 0){
				$post['status'] = 'Assigned_To_Rider';
				$msg = "Rider is assigned to an order, If you want to see, plz click on ok button.";
				$this->main->addData('rider_current_order', ['rider_id' => $post['rider_id'], 'order_id' => $order_id]);

				$this->main->addData('web_notification', [
					'user_id' 	 => $post['rider_id'],
					'order_id' 	 => $order_id,
					'type' 		 => 'request',
					'msg'		 => 'You have assigned new order, If you want to see, plz click on ok button.',
					'created_at' => date('Y-m-d H:i:s')
				]);
				$exist_device = $this->main->exist('device', ['user_id' => $post['rider_id']]);
				if($exist_device){
					$title   = "Assigned New Order";
					$message = "You have assigned new order and the order id is : $order_id";
					$device_id = $exist_device->device_id;
					$fields = array
					(
						'to'  => $device_id,
						'notification' => array(
							'body' => $message,
							'title' => $title,
							'priority' => 'high',
							"content_available"=> true
						)
					);
					$message_id = $this->sendPushNotification($fields);
					$this->db->insert('notification', [
						'device_id'	 => $exist_device->device_id,
						'user_id'    => $post['rider_id'],
						'message_id' => $message_id,
						'msg'		 => json_encode($fields)
					]);
				}
			}
			
			if($this->main->update('orders', $order_id, $post)){
				$this->main->addData('web_notification', [
					'user_id' 	 => $order_last_details->restaurant_id,
					'order_id' 	 => $order_id,
					'type' 		 => 'request',
					'msg'		 => $msg,
					'created_at' => date('Y-m-d H:i:s')
				]);
				$this->session->set_flashdata('feedback',"Order Updated Successfully.");
				redirect(base_url('panel/requestOrder'));
			}else{
				$this->session->set_flashdata('error',"Order Not Update. Plz Try Again!.");
				redirect(base_url('ngo/editRequestOrder').'/'.$order_id);
			}//end if else model
		}
	} // finalRequestOrder


	public function editDonateOrder($order_id){
		$donate_order = $this->main->getDonateOrder($order_id);
		if(isset($donate_order) && $donate_order){
			$this->data['rs_order'] 	= $donate_order;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'orders';

			$this->data['order_status'] = $donate_order[0]->status;

			$this->data['order_id'] = $order_id;

			$this->data['restaurant_name'] = $this->main->view('users', ['id' => $donate_order[0]->restaurant_id], false, 'single', 1, 'full_name');
			$this->data['ngo_name'] 	   = $this->main->view('users', ['id' => $donate_order[0]->ngo_id], false, 'single', 1, 'full_name');

			$all_riders = $this->main->view('users', ['role' => 'rider', 'status' => 1, 'available' => 1]);

			$available_riders = [];
			foreach($all_riders as $rec_rider){
				$rider_exist = $this->main->exist('rider_current_order', ['rider_id' => $rec_rider->id]);
				if(!$rider_exist){
					$available_riders[] = $rec_rider;
				}
			}
			$this->data['all_riders'] = $available_riders;

			if(!empty($donate_order[0]->rider_id)){
				$this->data['rider_name'] = $this->main->view('users', ['id' => $donate_order[0]->rider_id], false, 'single', 1, 'full_name');
			}else{
				$this->data['rider_name'] = '';
			}
			$this->load->view('orders/donate/edit_donate_order', $this->data);
		}else{
			$this->session->set_flashdata('error',"Invalid Order");
			redirect(base_url('panel/donateOrder'));
		}
	} // editDonateOrder

	public function finalDonateOrder(){
		if($_POST){
			$post = $this->input->post();
			
			$order_id = $post['order_id'];
			$order_last_details = $this->main->view('orders', ["id" => $post['order_id']], false, 'single');
			
			unset($post['order_id']);

			$msg = "Your order is updated now, If you want to see, plz click on ok button.";
			if($post['status'] == 'Accepted' && $order_last_details->status != 'Accepted'){
				$msg = "Your order is accepted now, If you want to see, plz click on ok button.";
			}		

			if(isset($post['rider_id']) && $post['rider_id'] > 0){
				$post['status'] = 'Assigned_To_Rider';
				$msg = "Rider is assigned to your order, If you want to see, plz click on ok button.";
				$this->main->addData('rider_current_order', ['rider_id' => $post['rider_id'], 'order_id' => $order_id]);

				$this->main->addData('web_notification', [
					'user_id' 	 => $post['rider_id'],
					'order_id' 	 => $order_id,
					'type' 		 => 'donate',
					'msg'		 => 'You have assigned new order, If you want to see, plz click on ok button.',
					'created_at' => date('Y-m-d H:i:s')
				]);

				$exist_device = $this->main->exist('device', ['user_id' => $post['rider_id']]);
				if($exist_device){
					$title   = "Assigned New Order";
					$message = "You have assigned new order and the order id is : $order_id";
					$device_id = $exist_device->device_id;

					$fields = array
					(
						'to'  => $device_id,
						'notification' => array(
							'body' => $message,
							'title' => $title,
							'priority' => 'high',
							"content_available"=> true
						)
					);
					$message_id = $this->sendPushNotification($fields);
					$this->db->insert('notification', [
						'device_id'	 => $exist_device->device_id,
						'user_id'    => $post['rider_id'],
						'message_id' => $message_id,
						'msg'		 => json_encode($fields)
					]);
				}
			}
			
			if($this->main->update('orders', $order_id, $post)){
				$this->main->addData('web_notification', [
					'user_id' 	 => $order_last_details->restaurant_id,
					'order_id' 	 => $order_id,
					'type' 		 => 'donate',
					'msg'		 => $msg,
					'created_at' => date('Y-m-d H:i:s')
				]);
				$this->session->set_flashdata('feedback',"Order Updated Successfully.");
				redirect(base_url('panel/donateOrder'));
			}else{
				$this->session->set_flashdata('error',"Order Not Update. Plz Try Again!.");
				redirect(base_url('ngo/editDonateOrder').'/'.$order_id);
			}//end if else model
		}
	} // finalDonateOrder

	function sendPushNotification($fields = []){
		$SERVER_KEY = 'AAAAFTqKx0M:APA91bFAsmz76y_hjcsllSU8vo-IuR3J0gHotlwfhmn7lM5bpMcDkvAF08xXQSRv-1rWlzeLX6bAJpvRWoD_dzkkArBsxe4GoDMzdrDvlnxWV5LwWD9IDe1nkZrCqlnFE8IzeYH1KfqT';
		$headers = array
		(
			'Authorization: key=' . $SERVER_KEY,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		$result = json_decode($result, true);
		curl_close( $ch );
		return $result['results'][0]['message_id'];
	} // sendPushNotification

	public function signout(){
		$this->_signout();
	}//signout
}