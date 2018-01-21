<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->database();
        $this->load->model('CabinetModel');
        $this->load->model('AjaxModel');
	}

	public function index()
	{

		$data = $this->AjaxModel->recoverCompetition(2);
		echo '<pre>';
		echo '<h2> ALL=' . count($data) . '</h2>';
		var_dump($data);
		echo '</pre>';
	}
}
