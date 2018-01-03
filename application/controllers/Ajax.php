<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('AjaxModel');
		$this->load->model('CabinetModel');
		$this->load->library('session');
		$this->load->library('pagination');
                $this->load->helper('download');
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
      
	public function getUserInfo(){
		$id=$_POST['id'];
		$user=$this->AjaxModel->getUserInfo($id);
		echo json_encode($user);
	}

	public function saveUser()
	{
            $res = $this->AjaxModel->saveUser($_POST);
            var_dump($res);
	}

	public function filterUser(){
		$users=$this->AjaxModel->filterUsers($_POST);
		echo json_encode($users);
	}

	public function delRole()
	{
		var_dump($this->AjaxModel->delRole($_POST['role']));
	}

	public function addRole()
	{
		var_dump($this->AjaxModel->addRole($_POST['role']));
	}

	public function getRoles()
	{
		$roles= array(
			'admin' => $this->session->admin,
			'organizer' => $this->session->organizer,
			'cluber' => $this->session->cluber,
			'trainer' => $this->session->trainer,
			'dancer' => $this->session->dancer,
			);
		echo json_encode($roles);
	}

    public function getClubesHtml()
    {
        echo $this->CabinetModel->clubesHtml($_POST['city']);
    }

    public function getCitiesHtml()
    {
        echo $this->CabinetModel->citiesHtml($_POST['region']);
    }

    public function getTrainersHtml()
    {
        echo $this->CabinetModel->trainersHtml($_POST['club']);
    }

    public function edit() 
    {
        $id=$_POST['id'];
        $table=$_POST['table'];
        $res=$this->AjaxModel->getRow($table,$id);
        echo json_encode($res);
    }
    
    public function save()
    {
        $table=$_POST['table'];
        $id=$_POST['id'];
        $data=[];
        $d=$_POST;
        foreach ($d as $k => $v){
            if ($k != 'id' && $k != 'table'){
                $data[$k]=$v;
            }
        }
        $ins=$this->AjaxModel->update($table,$id,$data);
        echo $ins;
        //var_dump($data);
    }
    
    public function delete()
    {
        $table=$_POST['table'];
        $id=$_POST['id'];
        if (isset($_POST['soft'])){
            $soft=$_POST['soft'];
        } else {
            $soft=1;
        }
        $res=$this->AjaxModel->delete($table,$id,$soft);
        echo $res;
    }
    
    public function insert()
    {
        $table=$_POST['table'];
        $data=[];
        $d=$_POST;
        foreach ($d as $k => $v){
            if ($k != 'table'){
                $data[$k]=$v;
            }
        }
        $ins=$this->AjaxModel->insert($table,$data);
        echo $ins;
    }
    
    public function showWays()
    {
        echo $this->CabinetModel->htmlWays();
    }
    
    public function showStyles()
    {
        echo $this->AjaxModel->htmlStyles($_POST['way']);
    }
    
    public function selectStyles()
    {
        //echo $_POST['way'];
        echo $this->AjaxModel->selectStyles($_POST['way']);
    }
    
    public function showCounts()
    {
        echo $this->CabinetModel->HtmlCounts();
    }

    public function showLigs()
    {
        echo $this->AjaxModel->htmlLigs($_POST['way']);
    }
    
    public function showAges()
    {
        echo $this->CabinetModel->htmlAges();
    }
	
    public function showAgeLig()
    {
        echo $this->AjaxModel->htmlAgeLig($_POST['way']);
    }
    
    public function dancerInfo()
    {
        $id=$_POST['id'];
        $res=$this->AjaxModel->getDancer($id);
        echo json_encode($res[0]);
    }
    
    public function saveDancer()
    {
        $data=$_POST;
        $ins=$this->AjaxModel->updateDancer($data);
        var_dump($ins);
        //echo $ins;
    }
    
    public function showTrainerDancers()
    {   
        $trainer_id=$this->session->id;
        echo $this->CabinetModel->htmlTrainerDancers($trainer_id);
    }
    
    public function showTrainerDancers2()
    {   
        $trainer_id=$_POST['id'];
        echo $this->CabinetModel->htmlTrainerDancers2($trainer_id);
    }
    
    public function deactivateDancer()
    {   
        $id=$_POST['id'];
        echo $this->AjaxModel->deactivateDancer($id);
    }
    
    public function activateDancer()
    {   
        $id=$_POST['id'];
        echo $this->AjaxModel->activateDancer($id);
    }
    
    public function showCluberTrainers()
    {   
        $cluber_id=$this->session->id;
        echo $this->CabinetModel->htmlCluberTrainers($cluber_id);
    }
    
    public function trainerInfo()
    {
        $id=$_POST['id'];
        $res=$this->AjaxModel->getTrainer($id);
        echo json_encode($res[0]);
    }

    public function deactivateTrainer()
    {   
        $id=$_POST['id'];
        echo $this->AjaxModel->deactivateTrainer($id);
    }
    
    public function activateTrainer()
    {   
        $id=$_POST['id'];
        echo $this->AjaxModel->activateTrainer($id);
    }
    
    public function saveTrainer()
    {
        $data=$_POST;
        $ins=$this->AjaxModel->updateTrainer($data);
        var_dump($ins);
        //echo $ins;
    }
    
    public function IUser(){
        $id=$this->session->id;
        $user=$this->CabinetModel->showUser($id);
        //var_dump($user);
        echo json_encode($user);
    }
    
    public function selectOrg()
    {
        echo $this->AjaxModel->selectOrg();
    }
    
    public function addCompetition()
    {
        echo $this->AjaxModel->addCompetition($_POST);
    }
    
    public function updateCompetition()
    {
        
        echo $this->AjaxModel->updateCompetition($_POST);
    }
    
    public function showCompetitions()
    {
        echo $this->CabinetModel->htmlCompetitions($_POST['role']);
    }
    
    public function compInfo()
    {
        $res = $this->AjaxModel->compInfo($_POST['id']);
        echo json_encode($res);
    }
    
    public function addDancer()
    {
        $trainer_id=$this->AjaxModel->getTrainerId($this->session->id);
        echo $this->AjaxModel->addDancer($_POST,$trainer_id);
    }
    
    public function selectLigs()
    {
        echo $this->AjaxModel->selectLigs($_POST['way_id']);
    }
    
    public function showExp()
    {
        $exp = $this->CabinetModel->dancerExpHtml($_POST['id']);
        echo $exp['exp'];
    }
    
    public function saveExp()
    {
        echo $ins=$this->AjaxModel->saveExp($_POST);
    }
    
    public function addSummCats()
    {
        $ins= $this->AjaxModel->addSummCats($_POST);
        echo $ins;
        
    }
    
    public function getCompListTrainer()
    {
        $trainer_id = $this->AjaxModel->getTrainerId($this->session->id);
        $comp_id = $_POST['comp_id'];
        $list = $this->AjaxModel->getCompListHtml($comp_id, 'trainer', $trainer_id);
        echo $list;
    }
    
    public function getCompListCluber()
    {
        $club_id = $this->AjaxModel->getClubId($this->session->id);
        $comp_id = $_POST['comp_id'];
        $list = $this->AjaxModel->getCompListHtml($comp_id, 'cluber', $club_id);
        echo $list;
    }
    
    public function getCompListAdmin()
    {
        $comp_id = $_POST['comp_id'];
        $list = $this->AjaxModel->getCompListHtml($comp_id, 'admin');
        echo $list;
    }
    
    public function getCompListAdmin2()
    {
        echo $this->AjaxModel->AdminCompList($_POST['comp_id'], 'admin');
    }
    
    public function savePays()
    {
        $res=$this->AjaxModel->savePays($_POST);
        var_dump($res);
    }

    public function getCompReward()
    {
        echo $this->AjaxModel->getCompReward($_POST['comp_id']);
        
    }
    
    public function setNumbers()
    {
        echo $this->CabinetModel->setNumbers($_POST['comp_id']);
    }
    
    public function getNumbers()
    {
        $res=$this->CabinetModel->getNumbers($_POST['comp_id']);
        echo json_encode($res);
    }
    
    public function uploadResult()
    {
        $comp_id = $_POST['comp_id'];
        $f=move_uploaded_file($_FILES[0]['tmp_name'],'csv/'.$_FILES[0]['name']);
        $file=$_FILES[0]['name'];
        $res=$this->AjaxModel->uploadResult($file, $comp_id);
        echo $res;
    }
    
    public function getResult()
    {
        $comp_id=$_POST['comp_id'];
        $data=$this->AjaxModel->getResultCsv($comp_id, 'admin');
        echo $data;
    }
    
    public function getResultHtml()
    {
        $comp_id=$_POST['comp_id'];
        //$data=$comp_id;
        $data=$this->AjaxModel->getResultHtml($comp_id, 'admin');
        echo $data;
    }
    
    public function doneComp()
    {
        $comp_id = $_POST['comp_id'];
        $res = $this->AjaxModel->doneComp($comp_id);
        echo $res;
    }
    
    public function closeComp()
    {
        $comp_id = $_POST['comp_id'];
        $res = $this->AjaxModel->closeComp($comp_id);
        echo $res;
    }

    public function getYearPay()
    {
        echo $this->AjaxModel->getYearPay($_POST['type']);
    }
    
    public function getYearPay2()
    {
        echo json_encode($this->AjaxModel->getYearPay2($_POST['type'], $_POST['col'], $_POST['page']));
        //var_dump($this->AjaxModel->getYearPay2($_POST['type'], $_POST['col'], $_POST['page']));
    }
    
  
    public function saveYearPays()
    {
        $res=$this->AjaxModel->saveYearPays($_POST);
        var_dump($res);
    }
    
    public function showStat(){
        $style_id = $_POST['style'];
        $res=$this->AjaxModel->showStat($style_id);
        echo $res;
    }
    
    public function selectTrainers()
    {
        echo $this->AjaxModel->selectTrainers($_POST['id']);
    }
    
    public function selectDancers()
    {
        echo $this->AjaxModel->selectDancers($_POST['id']);
    }
    
    public function delPart()
    {
        echo $this->AjaxModel->delPart($_POST['id']);
    }
    
    public function test()
    {
        echo "TEST <br>";
   
    }
}
