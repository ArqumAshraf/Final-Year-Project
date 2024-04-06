<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {
	public function view($tab, $fields=false, $order_by=false, $result="all", $single_field=0, $specific_field="*", $result_type='object'){
		$query = $this->db->select($specific_field);
		if($fields){
			$query = $this->db->where($fields);
		}
		if($order_by){
			$key = $order_by[0]; $val = $order_by[1];
			$query = $this->db->order_by($key, $val);
		}
		$query = $this->db->get($tab);

		if($result=="all"){
			if($result_type == 'object'){
				return $query->result();
			}else{
				return $query->result_array();
			}
		}else{
			return $single_field == 1 ? $query->row()->$specific_field : $query->row();
		}	
	} //viewAll

	public function addData($tab, $data){
		$this->db->insert($tab, $data);
		return $this->db->insert_id();
	} //addData

	public function update($tab, $id, $data){
		return $this->db->where('id', $id)->update($tab, $data);
	} //update

	public function getStock($ngo_id){
		return $this->db->select("item_id, item_image, item_name, qty")->from("item_stock")->join("food_item", "food_item.id = item_stock.item_id")->where("item_stock.ngo_id", $ngo_id)->get()->result();
	} //getStock

	public function getRequestOrder($order_id){
		return $this->db->select("orders.id, orders.ngo_id, orders.restaurant_id, orders.rider_id, orders.order_type, orders.status, order_details.id order_detail_id, order_details.item_id, order_details.request_qty, order_details.donate_qty, food_item.item_name, food_item.item_image")
						->from("orders")
						->join("order_details", "order_details.order_id = orders.id")
						->join("food_item", "food_item.id = order_details.item_id")
						->where(["orders.order_type" => "request", "orders.id" => $order_id])
						->get()
						->result();
	} //getRequestOrder

	public function getDonateOrder($order_id){
		return $this->db->select("orders.id, orders.ngo_id, orders.restaurant_id, orders.rider_id, orders.order_type, orders.status, order_details.id order_detail_id, order_details.item_id, order_details.donate_qty, food_item.item_name, food_item.item_image")
						->from("orders")
						->join("order_details", "order_details.order_id = orders.id")
						->join("food_item", "food_item.id = order_details.item_id")
						->where(["orders.order_type" => "donate", "orders.id" => $order_id])
						->get()
						->result();
	} //getDonateOrder

	public function getAvailableOrder($order_id){
		return $this->db->select("orders.id, orders.ngo_id, orders.restaurant_id, orders.rider_id, orders.order_type, orders.status, order_details.id order_detail_id, order_details.item_id, order_details.donate_qty, food_item.item_name, food_item.item_image")
						->from("orders")
						->join("order_details", "order_details.order_id = orders.id")
						->join("food_item", "food_item.id = order_details.item_id")
						->where("orders.id", $order_id)
						->get()
						->result();
	} //getAvailableOrder


	public function exist($tab, $field){
		$query = $this->db->where($field)->get($tab);
		if($query->num_rows()){
			return $query->row();
		}else{
			return false;
		}
	} //exist


	public function delete($tab, $key, $val){
		return $this->db->delete($tab, [$key=>$val]);
	} //delete

	function getRowsPagination($params = array(), $table_name=''){ 
        $this->db->select('*'); 
        $this->db->from($table_name); 
         
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 
         
        if(array_key_exists("search", $params)){ 
            // Filter data by searched keywords 
            if(!empty($params['search']['keywords'])){ 
				if($table_name == 'category'){
					$this->db->like('category_title', $params['search']['keywords']); 
					$this->db->or_like('category_name', $params['search']['keywords']);		
				}
				
				if($table_name == 'author'){
					$this->db->like('full_name', $params['search']['keywords']); 
					$this->db->or_like('email', $params['search']['keywords']);		
				}

				if($table_name == 'article'){
					$this->db->like('article_title', $params['search']['keywords']); 
					$this->db->or_like('article_name', $params['search']['keywords']);		
				}
            } 
        } 
         
        // Sort data by ascending or desceding order 
        if(!empty($params['search']['sortBy'])){ 
			if($table_name == 'category'){
				$this->db->order_by('category_title', $params['search']['sortBy']); 
			}
			
			if($table_name == 'author'){
				$this->db->order_by('full_name', $params['search']['sortBy']); 
			}

			if($table_name == 'article'){
				$this->db->order_by('article_title', $params['search']['sortBy']); 
			}
		}else{ 
            $this->db->order_by('id', 'desc'); 
        } 
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }else{ 
            if(array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')){ 
                if(!empty($params['id'])){ 
                    $this->db->where('id', $params['id']); 
                } 
                $query = $this->db->get(); 
                $result = $query->row_array(); 
            }else{ 
                $this->db->order_by('id', 'desc'); 
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
                 
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
        // Return fetched data 
        return $result; 
    } //getRowsPagination
}	