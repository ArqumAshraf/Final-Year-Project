<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rider extends MY_Controller {
	public $data = [];
	function __construct() {
		parent::__construct();
		if(!$this->session->userdata('id') || $this->session->userdata('role') != 'rider'){
			return redirect(base_url('login'));
		}
		$this->data["current_user_id"] = $this->user_id = $this->session->userdata('id');
	}//end construct
	
	public function index(){
		redirect(base_url('rider/profile'));
	}//index

	public function dashboard(){
		$this->load->view('dashboard', $this->data);
	}//index
	
	public function profile(){
		$this->data['current_slug'] = 'My Profile';
		$this->data['current_page'] = 'my_profile';

		if($_POST){
			if($this->form_validation->run('profile') == FALSE){
				$this->data['user_profile'] = $this->users->view_profile($this->user_id);
				$this->load->view('profile', $this->data);
			}else{
				$post 	   = $this->input->post();
				$user_pass = $this->input->post('user_pass');
				$img_id    = $this->input->post('img_id');

				if(isset($post['available']) && $post['available']){
					// nothing happend
				}else{
					$post['available'] = 0;
				}

				if(!empty($user_pass)){
					if(strlen($user_pass) >= 6){
						$post['user_pass'] = md5($this->input->post('user_pass'));
					}else{
						$this->session->set_flashdata('error', 'Password must be graterthan 5 digits');
						redirect(base_url('rider/profile'));
					}
				}else{
					if(isset($_POST['user_pass'])){
						unset($post['user_pass']);
					}
				}
				$profile_pic = $_FILES['profile_pic'];
					
				if(!empty($profile_pic['name'])){
					$img_path = 'profile/rider/';
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
					redirect(base_url('rider/profile'));
				}else{
					$this->session->set_flashdata('error',"Profile Not Update. Plz Try Again!.");
					redirect(base_url('rider/profile'));
				}//model
			}//validation
		}else{
			$this->data['user_profile'] = $this->users->view_profile($this->user_id);
			$this->load->view('profile', $this->data);
		}//post
	}//profile

	public function currentOrder(){
		$order_detail = $this->main->view('rider_current_order', ['rider_id' => $this->user_id], false, 'single');
		if($order_detail){
			$order_id = $order_detail->order_id;
			$current_order = $this->main->getAvailableOrder($order_id);
			
			$this->data['rs_restaurant'] = $this->main->view('users', ['id' => $current_order[0]->restaurant_id], false, 'single');
			$this->data['ngo_name']    	 = $this->main->view('users', ['id' => $current_order[0]->ngo_id], false, 'single', 1, 'full_name');

			$this->data['rs_order'] 	= $current_order;
			$this->data['order_id'] 	= $order_id;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'current_order';
			$this->load->view('orders/rider/view', $this->data);
		}else{
			$this->session->set_flashdata('error',"Right now you dont have any order");
			redirect(base_url('rider/orderHistory'));
		}
		
	} //currentOrder

	public function orderDeliver($order_id){
		$order_details = $this->main->getAvailableOrder($order_id);
		if($order_details){
			$ngo_id 	   = $order_details[0]->ngo_id;
			$restaurant_id = $order_details[0]->restaurant_id;
			$ngo_id 	   = $order_details[0]->ngo_id;

			$order_last_details = $this->main->view('orders', ["id" => $order_id], false, 'single');

			foreach($order_details as $rec_order){
				$stock_exist = $this->main->view('item_stock', ['ngo_id' => $ngo_id, 'item_id' => $rec_order->item_id], false, 'single');
				if($stock_exist){
					$this->main->update('item_stock', $stock_exist->id, ['qty' => $stock_exist->qty+$rec_order->donate_qty]);
				}else{
					$this->main->addData('item_stock', [
						'item_id' 	 => $rec_order->item_id,
						'ngo_id' 	 => $ngo_id,
						'qty' 		 => $rec_order->donate_qty
					]);
				}
			}

			if($this->main->update('orders', $order_id, ['status' => 'Completed'])){
				$this->main->addData('web_notification', [
					'user_id' 	 => $order_last_details->restaurant_id,
					'order_id' 	 => $order_id,
					'type' 		 => $order_last_details->order_type,
					'msg'		 => 'Order is delivered now, If you want to see, plz click on ok button.',
					'created_at' => date('Y-m-d H:i:s')
				]);

				$this->main->addData('web_notification', [
					'user_id' 	 => $order_last_details->ngo_id,
					'order_id' 	 => $order_id,
					'type' 		 => $order_last_details->order_type,
					'msg'		 => 'Order is delivered now, If you want to see, plz click on ok button.',
					'created_at' => date('Y-m-d H:i:s')
				]);
				$this->main->delete('rider_current_order', 'order_id', $order_id);
				$this->session->set_flashdata('feedback',"Order Complete Successfully.");
				redirect(base_url('rider/orderHistory'));
			}else{
				$this->session->set_flashdata('error',"Order not complete plz try again later!");
				redirect(base_url('rider/orderHistory'));
			}
		}else{
			$this->session->set_flashdata('error',"Order not found");
			redirect(base_url('rider/orderHistory'));
		}
	} //orderDeliver

	public function orderHistory(){
		$where = ['rider_id' => $this->user_id];

		if($_POST){
			$where['id'] = $this->input->post('order_id');
			$this->data['current_order_id']  = $this->input->post('order_id');
			$this->data['rs_orders'] = $this->main->view('orders', $where);
		}else{
			$this->data['rs_orders'] = $this->main->view('orders', $where, ['id', 'desc']);
		}
		$this->data['current_slug'] = 'Order History';
		$this->data['current_page'] = 'order_history';
		$this->load->view('orders/rider/history', $this->data);
	} //orderHistory

	public function historyView($order_id){
		$order_details = $this->main->getAvailableOrder($order_id);
		if($order_details){
			$this->data['rs_restaurant'] = $this->main->view('users', ['id' => $order_details[0]->restaurant_id], false, 'single');
			$this->data['ngo_name']    	 = $this->main->view('users', ['id' => $order_details[0]->ngo_id], false, 'single', 1, 'full_name');

			$this->data['rs_order'] 	= $order_details;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'orders';
			$this->load->view('orders/rider/history_view', $this->data);
		}else{
			$this->session->set_flashdata('error',"Order not found");
			redirect(base_url('rider/orderHistory'));
		}
	} // historyView

	public function signout(){
		$this->_signout();
	}//signout
}