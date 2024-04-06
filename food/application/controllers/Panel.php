<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Panel extends MY_Controller {
	public $data = [];
	function __construct() {
		parent::__construct();
		if(!$this->session->userdata('id') || $this->session->userdata('role') == 'rider'){
			return redirect(base_url('login'));
		}
		$this->data["current_user_id"] = $this->user_id = $this->session->userdata('id');
	}//end construct

	public function foodItem($action="", $id=0){
		if($action=="add"){
			if($_POST){
				if($this->form_validation->run('addFoodItem') == FALSE){
					$this->session->set_flashdata('error',validation_errors());
					redirect(base_url('panel/foodItem/add'));		
				}else{
					$post = $this->input->post();
					$post['user_id'] = $this->user_id;
					if(!empty($_FILES['item_image'])){
						$item_image = $_FILES['item_image'];
						if($new_name = $this-> _upload_file($item_image['name'], $item_image['type'], $item_image['tmp_name'], $item_image['size'], $img_path = 'food_item/')){
							$post['item_image'] = $new_name;
						}
					}

					if($this->main->addData('food_item', $post)){
						$this->session->set_flashdata('feedback',"Create Item Successfully.");
						redirect(base_url('panel/foodItem/manage'));
					}else{
						$this->session->set_flashdata('error',"Item Not Create. Plz Try Again!.");
						redirect(base_url('panel/foodItem/manage'));
					}//end if else model
				}//end else valid	
			}else{
				$this->data['current_slug'] = 'Add Item';
				$this->data['current_page'] = 'foodItem_add';
				$this->load->view('food_item/add', $this->data);
			}//end else post
		}//add
		
		if($action=="edit"){
			if($_POST){
				$post = $this->input->post();
				unset($post['img_id']);
				$img_id = $this->input->post('img_id');

				if(!empty($_FILES['item_image'])){
					$item_image = $_FILES['item_image'];
					$img_path = 'food_item/';
					if($new_name = $this-> _upload_file($item_image['name'], $item_image['type'], $item_image['tmp_name'], $item_image['size'], $img_path)){
						$post['item_image'] = $new_name;
						if(!empty($img_id)){
							unlink('./assets/'.$img_path.$img_id);
						}
					}else{
						$post['item_image'] = $img_id;	
					}
				}

				if($this->main->update('food_item', $id, $post)){
					$this->session->set_flashdata('feedback',"Item Updated Successfully.");
					redirect(base_url('panel/foodItem/manage'));
				}else{
					$this->session->set_flashdata('error',"Item Not Update. Plz Try Again!.");
					redirect(base_url('panel/foodItem/manage'));
				}//end if else model
			}else{
				$this->data['rs_view'] = $this->main->view('food_item', ['id' => $id], false, 'single');
				$this->data['current_slug'] = 'Edit Item';
				$this->data['current_page'] = 'foodItem';
				$this->load->view('food_item/edit', $this->data);
			}//end else post
		}//edit
		
		if($action=="manage"){
			$this->data['user_id']   = $this->user_id;
			$this->data['rs_manage'] = $this->main->view('food_item', false, ['id', 'desc']);
			$this->data['current_slug'] = 'Item List';
			$this->data['current_page'] = 'foodItem_list';
			$this->load->view('food_item/manage', $this->data);
		}//manage
	}//foodItem

	public function requestOrder(){
		$where = ['order_type' => 'request'];
		if($this->data["current_role"] == 'ngo'){
			$where['ngo_id'] = $this->user_id;
		}else if($this->data["current_role"] == 'restaurant'){
			$where['restaurant_id'] = $this->user_id;
		}

		if($_POST){
			$where['id'] = $this->input->post('order_id');
			$this->data['current_order_id']  = $this->input->post('order_id');
			$this->data['rs_orders'] = $this->main->view('orders', $where);
		}else{
			$this->data['rs_orders'] = $this->main->view('orders', $where, ['id', 'desc']);
		}

		$users = $this->main->view('users');
		$all_users = [];
		if(isset($users) && !empty($users)){
			foreach($users as $rec_user){
				$all_users[$rec_user->id] = $rec_user->full_name;
			}
		}

		$this->data['all_users'] 	= $all_users;
		$this->data['current_slug'] = 'Request Order';
		$this->data['current_page'] = 'request_order';
		$this->load->view('orders/request', $this->data);
	} //requestOrder

	public function viewRequestOrder($order_id){
		$request_order = $this->main->getRequestOrder($order_id);
		if(isset($request_order) && $request_order){
			$this->data['rs_order'] 	= $request_order;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'orders';

			$this->data['order_status'] = $request_order[0]->status;

			$this->data['restaurant_name'] = $this->main->view('users', ['id' => $request_order[0]->restaurant_id], false, 'single', 1, 'full_name');
			$this->data['ngo_name'] 	   = $this->main->view('users', ['id' => $request_order[0]->ngo_id], false, 'single', 1, 'full_name');
	
			if(!empty($request_order[0]->rider_id)){
				$this->data['rider_name'] = $this->main->view('users', ['id' => $request_order[0]->rider_id], false, 'single', 1, 'full_name');
			}else{
				$this->data['rider_name'] = '';
			}
			$this->load->view('orders/view_request', $this->data);
		}else{
			$this->session->set_flashdata('error',"Invalid Order");
			redirect(base_url('panel/requestOrder'));
		}
	} // viewRequestOrder

	public function donateOrder(){
		$where = ['order_type' => 'donate'];
		if($this->data["current_role"] == 'ngo'){
			$where['ngo_id'] = $this->user_id;
		}else if($this->data["current_role"] == 'restaurant'){
			$where['restaurant_id'] = $this->user_id;
		}

		if($_POST){
			$where['id'] = $this->input->post('order_id');
			$this->data['current_order_id']  = $this->input->post('order_id');
			$this->data['rs_orders'] = $this->main->view('orders', $where);
		}else{
			$this->data['rs_orders'] = $this->main->view('orders', $where, ['id', 'desc']);
		}

		$users = $this->main->view('users');
		$all_users = [];
		if(isset($users) && !empty($users)){
			foreach($users as $rec_user){
				$all_users[$rec_user->id] = $rec_user->full_name;
			}
		}


		// echo '<pre>';
		// print_r($this->data['rs_orders']);
		// exit;

		$this->data['all_users'] 	= $all_users;
		$this->data['current_slug'] = 'Donate Order';
		$this->data['current_page'] = 'donate_order';
		$this->load->view('orders/donate/view', $this->data);
	} //donateOrder

	public function viewDonationOrder($order_id){
		$donate_order = $this->main->getDonateOrder($order_id);
		//$donate_order[0]->id
		if(isset($donate_order) && $donate_order){
			$this->data['rs_order'] 	= $donate_order;
			$this->data['current_slug'] = 'Order # '.$order_id;
			$this->data['current_page'] = 'orders';

			$this->data['order_status'] = $donate_order[0]->status;

			$this->data['restaurant_name'] = $this->main->view('users', ['id' => $donate_order[0]->restaurant_id], false, 'single', 1, 'full_name');
			$this->data['ngo_name'] 	   = $this->main->view('users', ['id' => $donate_order[0]->ngo_id], false, 'single', 1, 'full_name');

			if(!empty($donate_order[0]->rider_id)){
				$this->data['rider_name'] = $this->main->view('users', ['id' => $donate_order[0]->rider_id], false, 'single', 1, 'full_name');
			}else{
				$this->data['rider_name'] = '';
			}
			$this->load->view('orders/donate/view_order', $this->data);
		}else{
			$this->session->set_flashdata('error',"Invalid Order");
			redirect(base_url('panel/requestOrder'));
		}
	} // viewDonationOrder
}