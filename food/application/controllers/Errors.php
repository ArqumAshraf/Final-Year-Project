<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {
	function __construct() {
		 parent::__construct();
	}//end construct
	
	public function index(){
		$this->load->view("404");
	}//end index	
}