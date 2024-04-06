<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurant extends MY_Controller {
	public $data = [];
	function __construct() {
		parent::__construct();
		if(!$this->session->userdata('id') || $this->session->userdata('role') != 'restaurant'){
			return redirect(base_url('login'));
		}
		$this->data["current_user_id"] = $this->user_id = $this->session->userdata('id');
	}//end construct
	
	
	public function index(){
		redirect(base_url('restaurant/profile'));
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
						redirect(base_url('restaurant/profile'));
					}
				}else{
					if(isset($_POST['user_pass'])){
						unset($post['user_pass']);
					}
				}
				$profile_pic = $_FILES['profile_pic'];
					
				if(!empty($profile_pic['name'])){
					$img_path = 'profile/restaurant/';
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
					redirect(base_url('restaurant/profile'));
				}else{
					$this->session->set_flashdata('error',"Profile Not Update. Plz Try Again!.");
					redirect(base_url('restaurant/profile'));
				}//model
			}//validation
		}else{
			$this->data['user_profile'] = $this->users->view_profile($this->user_id);
			$this->load->view('profile', $this->data);
		}//post
	}//profile

	public function requestOrder($order_id){
		$request_order = $this->main->getRequestOrder($order_id);
		if(isset($request_order) && $request_order){
			$this->data['rs_order'] 	= $request_order;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'orders';

			$this->data['order_status'] = $request_order[0]->status;

			$this->data['order_id'] = $order_id;

			$this->data['restaurant_name'] = $this->main->view('users', ['id' => $request_order[0]->restaurant_id], false, 'single', 1, 'full_name');
			$this->data['ngo_name'] 	   = $this->main->view('users', ['id' => $request_order[0]->ngo_id], false, 'single', 1, 'full_name');

			if(!empty($request_order[0]->rider_id)){
				$this->data['rider_name'] = $this->main->view('users', ['id' => $request_order[0]->rider_id], false, 'single', 1, 'full_name');
			}else{
				$this->data['rider_name'] = '';
			}
			$this->load->view('orders/edit_view_request', $this->data);
		}else{
			$this->session->set_flashdata('error',"Invalid Order");
			redirect(base_url('panel/requestOrder'));
		}
	} // requestOrder

	public function editRequestOrder(){
		if($_POST){
			$post = $this->input->post();

			$order_last_details = $this->main->view('orders', ["id" => $post['order_id']], false, 'single');

			if($order_last_details->status == $post['status']){
				$msg = "Your order is updated now, If you want to see, plz click on ok button.";
			}else if($post['status'] == 'Accepted' && $order_last_details->status != 'Accepted'){
				$msg = "Your order is accepted now, If you want to see, plz click on ok button.";
			}			

			foreach($post['donate_qty'] as $detail_id => $qty){
				$this->main->update('order_details', $detail_id, ['donate_qty' => $qty]);
			}

			if($this->main->update('orders', $post['order_id'], ['status' => $post['status']])){
				$this->main->addData('web_notification', [
					'user_id' 	 => $order_last_details->ngo_id,
					'order_id' 	 => $post['order_id'],
					'type' 		 => 'request',
					'msg'		 => $msg,
					'created_at' => date('Y-m-d H:i:s')
				]);

				$this->session->set_flashdata('feedback',"Order Updated Successfully.");
				redirect(base_url('panel/requestOrder'));
			}else{
				$this->session->set_flashdata('error',"Order Not Update. Plz Try Again!.");
				redirect(base_url('restaurant/requestOrder').'/'.$post['order_id']);
			}//end if else model
		}
	} // editRequestOrder


	public function donateOrder(){
		if($_POST){
			$post = $this->input->post();

			if($new_order_id = $this->main->addData('orders', 
				[
					'ngo_id' 		=> $post['ngo_id'],
					'restaurant_id' => $this->user_id,
					'order_type' 	=> 'donate',
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
							'donate_qty'  => $rqty
						];
						$this->main->addData('order_details', $item_data);
					}
				}

				$this->main->addData('web_notification', [
					'user_id' 	 => $post['ngo_id'],
					'order_id' 	 => $new_order_id,
					'type' 		 => 'donate',
					'msg'		 => 'You receive new order, If you want to see, plz click on ok button.',
					'created_at' => date('Y-m-d H:i:s')
				]);
				$this->session->set_flashdata('feedback',"Donate order create successfully.");
				redirect(base_url('panel/donateOrder'));
			}else{
				$this->session->set_flashdata('error',"Donate order not create plz try again later!");
				redirect(base_url('ngo/donateOrder'));
			}
		}else{
			$this->data['current_slug'] = 'Donate New Order';
			$this->data['current_page'] = 'orders';
			$this->data['rs_foods'] = $this->main->view('food_item', false, ['item_name', 'asc']);
			$this->data['rs_ngo'] = $this->main->view('users', ['status' => 1, 'role' => 'ngo'], ['full_name', 'asc']);
			$this->load->view('orders/donate/add', $this->data);
		}
	} // requestOrder

	public function donateEditOrder($order_id){
		$donate_order = $this->main->getDonateOrder($order_id);
		if(isset($donate_order) && $donate_order){
			$this->data['rs_order'] 	= $donate_order;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'orders';

			$this->data['order_status'] = $donate_order[0]->status;

			$this->data['order_id'] = $order_id;

			$this->data['restaurant_name'] = $this->main->view('users', ['id' => $donate_order[0]->restaurant_id], false, 'single', 1, 'full_name');
			$this->data['ngo_name'] 	   = $this->main->view('users', ['id' => $donate_order[0]->ngo_id], false, 'single', 1, 'full_name');
			$this->load->view('orders/donate/edit_view', $this->data);
		}else{
			$this->session->set_flashdata('error',"Invalid Order");
			redirect(base_url('panel/donateOrder'));
		}
	} // donateEditOrder

	public function editDonateOrder(){
		if($_POST){
			$post = $this->input->post();

			$order_last_details = $this->main->view('orders', ["id" => $post['order_id']], false, 'single');

			foreach($post['donate_qty'] as $detail_id => $qty){
				$this->main->update('order_details', $detail_id, ['donate_qty' => $qty]);
			}

			if($this->main->update('orders', $post['order_id'], ['status' => $post['status']])){
				$this->main->addData('web_notification', [
					'user_id' 	 => $order_last_details->ngo_id,
					'order_id' 	 => $post['order_id'],
					'type' 		 => 'donate',
					'msg'		 => 'Order is updated now, If you want to see, plz click on ok button.',
					'created_at' => date('Y-m-d H:i:s')
				]);

				$this->session->set_flashdata('feedback',"Order Updated Successfully.");
				redirect(base_url('panel/donateOrder'));
			}else{
				$this->session->set_flashdata('error',"Order Not Update. Plz Try Again!.");
				redirect(base_url('restaurant/donateEditOrder').'/'.$post['order_id']);
			}//end if else model
		}
	} // editDonateOrder

	public function signout(){
		$this->_signout();
	}//signout
}