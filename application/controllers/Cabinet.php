<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabinet extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('CabinetModel');
        $this->load->model('AjaxModel');
        $this->load->library('session');
        $this->load->library('pagination');
    }

    public function index()
    {
        $comp_list = $this->CabinetModel->mainCompList();
        $this->load->view('main',['list'=>$comp_list]);
    }

    public function user()
    {
        if ($this->session->id > 0) {
            $id=$this->session->id;
            $user=$this->CabinetModel->showUser($id);
            $this->load->view('user/index',['user'=>$user]);
        }
        else {
            $this->load->view('errors/error_access');
        }
    }

    public function dancer()
    {
        if ($this->session->dancer != 2) {
            $this->load->view('errors/error_access');
        }
        else {
            $dancer = $this->CabinetModel->is_dancer($this->session->id);
            if ($dancer) {
                $contact=$this->CabinetModel->dancerContact();
                $exp = $this->AjaxModel->danExpHtml('user',$this->session->id);
                $this->load->view('dancer/index',['contact'=>$contact,'exp'=>$exp]);
            }
            else {
                $regions = $this->CabinetModel->regions_html();
                $belly = $this->CabinetModel->bellyHtml();
                $this->load->view('dancer/regform',['regions' => $regions,'belly' => $belly]);
            }
        }
    }

    public function trainer()
    {
        if ($this->session->trainer != 2) {
            $this->load->view('errors/error_access');
        }
        else {
            $trainer = $this->CabinetModel->is_trainer($this->session->id);
            if ($trainer) {
                $this->load->view('trainer/index');
            }
            else {
                $regions = $this->CabinetModel->regions_html();
                $this->load->view('trainer/regform',['regions' => $regions]);
            }
        }
    }

    public function cluber()
    {
        if ($this->session->cluber != 2) {
            $this->load->view('errors/error_access');
        }
        else {
            $cluber = $this->CabinetModel->is_cluber($this->session->id);
            if ($cluber) {
                $this->load->view('cluber/index');
            }
            else {
                $regions = $this->CabinetModel->regions_html();
                $this->load->view('cluber/regform',['regions' => $regions]);
            }
        }
    }

    public function organizer()
    {
        if ($this->session->organizer != 2) {
            $this->load->view('errors/error_access');
        }
        else {
            $org = $this->CabinetModel->is_organizer($this->session->id);
            if ($org) {
            $statuses = $this->CabinetModel->selectStatuses();
            $regions = $this->CabinetModel->regions_html();
            $ways=$this->CabinetModel->selectWays();
            $competitions=$this->CabinetModel->htmlCompetitions('organizer');
            $data=array(
                'regions'=>$regions,
                'ways'=>$ways,
                'competitions'=>$competitions,
                'statuses'=>$statuses,
            );
                $this->load->view('organizer/competitions',$data);
            }
            else {
                $regions = $this->CabinetModel->regions_html();
                $this->load->view('organizer/regform',['regions'=>$regions]);
            }
        }
    }

   public function admin()
    {
        if ($this->session->admin != 2) $this->load->view('errors/error_access');
        else {
            $this->load->view('admin/index');
        }
    }

    public function adminUsers($id = 0)
    {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $config = $this->CabinetModel->paginate('users','index.php/cabinet/adminusers');
            $users = $this->CabinetModel->getUsers($id);
            $this->pagination->initialize($config);
            $this->load->view('admin/users',['users' => $users, 'page'=>$id]);
        }
    }

    public function adminways() {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $ways=$this->CabinetModel->htmlWays();
            $this->load->view('admin/ways',['ways' => $ways]);
        }
    }

    public function adminstyles() {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $ways=$this->CabinetModel->selectWays();
            $this->load->view('admin/styles',['ways' => $ways]);
        }
    }

    public function admincounts() {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $counts=$this->CabinetModel->HtmlCounts();
            $this->load->view('admin/counts',['counts' => $counts]);
        }
    }

    public function adminligs() {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $ways=$this->CabinetModel->selectWays();
            $this->load->view('admin/ligs',['ways' => $ways]);
        }
    }

    public function adminages() {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $ages=$this->CabinetModel->htmlAges();
            $this->load->view('admin/ages',['ages' => $ages]);
        }
    }

    public function adminagelig() {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $ages=$this->CabinetModel->selectAges();
            $ways=$this->CabinetModel->selectWays();
            $this->load->view('admin/ligage',['ways'=>$ways,'ages'=>$ages]);
        }
    }

    public function trainercontact() {
        if ($this->session->trainer != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $contact=$this->CabinetModel->trainerContact();
            $this->load->view('trainer/contact',['contact'=>$contact]);
        }
    }

    public function trainerdancers($trainer_id = 0) {

        if ($this->session->trainer != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $belly = $this->CabinetModel->bellyHtml();
            if ($trainer_id == 0){
                $trainer_id=$this->session->id;
                $dancers=$this->CabinetModel->htmlTrainerDancers($trainer_id);
                $this->load->view('trainer/dancers',['dancers'=>$dancers,'belly'=>$belly]);
            } else {
                $dancers=$this->CabinetModel->htmlTrainerDancers($trainer_id);
                $this->load->view('cluber/dancers',['dancers'=>$dancers,'belly'=>$belly,'trainer_id'=>$trainer_id]);
            }
        }
    }

    public function clubtrainers()
    {
        if ($this->session->cluber != 2){
            $this->load->view('errors/error_access');
        }
        else {
            //$belly = $this->CabinetModel->bellyHtml();
            $organiser_id=$this->session->id;
            $trainers=$this->CabinetModel->htmlCluberTrainers($organiser_id);
            $this->load->view('cluber/trainers',['trainers'=>$trainers]);
        }
    }

    public function clubcontact()
    {
        if ($this->session->trainer != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $contact=$this->CabinetModel->cluberContact();
            $this->load->view('cluber/contact',['contact'=>$contact]);
        }
    }

    public function admincompetitions(){
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $statuses = $this->CabinetModel->selectStatuses();
            $regions = $this->CabinetModel->regions_html();
            $ways=$this->CabinetModel->selectWays();
            $competitions=$this->CabinetModel->htmlCompetitions('admin');
            $data=array(
                'regions'=>$regions,
                'ways'=>$ways,
                'competitions'=>$competitions,
                'statuses'=>$statuses,
            );
            $this->load->view('admin/competitions',$data);
        }
    }

    public function trainercompetitions(){
        if ($this->session->trainer != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $statuses = $this->CabinetModel->selectStatuses();
            $regions = $this->CabinetModel->regions_html();
            $ways=$this->CabinetModel->selectWays();
            $competitions=$this->CabinetModel->htmlCompetitions('trainer');
            $data=array(
                'regions'=>$regions,
                'ways'=>$ways,
                'competitions'=>$competitions,
                'statuses'=>$statuses,
            );
            $this->load->view('trainer/competitions',$data);
        }
    }

    public function traineraddtocomp($id){
        if ($this->session->trainer != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $trainer_id = $this->AjaxModel->getTrainerId($this->session->id);
            $comp_list=$this->AjaxModel->getCompListHtml($id, 'trainer', $trainer_id);
            $dancers = $this->CabinetModel->allDancersToComp('trainer');
            //$list = $this->AjaxModel->getCompList2($id, 'trainer', $trainer_id);
            //$files = $this->AjaxModel->getCSVlist($list, $id, 'trainer');
            $file = $this->AjaxModel->genTrainerCsvFiles($id);
            $data=array(
                'dancers'=>$dancers,
                'comp_id'=>$id,
                'comp_list'=>$comp_list,
                'file'=>$file,
            );
            $this->load->view('trainer/adddancerstocomp',$data);
        }
    }

    public function trainercompinfo($id){
        if ($this->session->trainer != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $trainer_id = $this->AjaxModel->getTrainerId($this->session->id);
            $comp_list=$this->AjaxModel->getCompListHtml($id, 'trainer', $trainer_id);
            //$list = $this->AjaxModel->getCompList2($id, 'trainer', $trainer_id);
            $file = $this->AjaxModel->genTrainerCsvFiles($id);
            $data=array(
                'comp_id'=>$id,
                'comp_list'=>$comp_list,
                'file'=>$file,
            );
            $this->load->view('trainer/trainercompinfo',$data);
        }
    }

    public function compreglist()
    {
        if ($this->session->trainer != 2){
            $this->load->view('errors/error_access');
        }
        else{
        $list = $this->CabinetModel->getCatList($_POST);
        if (isset($_POST['dancer'])){
            $dancers = $this->CabinetModel->getDancersList($_POST['dancer']);
        } else{
            $dancers='';
        }
        $data=array(
            'list'=>$list,
            'comp_id'=>$_POST['comp_id'],
            'dancers'=>$dancers,
        );
        $this->load->view('trainer/select_summ_cat',$data);
        }
    }

    public function compreglist2()
    {
        if ($this->session->cluber != 2){
            $this->load->view('errors/error_access');
        }
        else{
        $list = $this->CabinetModel->getCatList($_POST);
        if (isset($_POST['dancer'])){
            $dancers = $this->CabinetModel->getDancersList($_POST['dancer']);
        } else{
            $dancers='';
        }
        $data=array(
            'list'=>$list,
            'comp_id'=>$_POST['comp_id'],
            'dancers'=>$dancers,
        );
        $this->load->view('cluber/select_summ_cat',$data);
        }
    }

    public function experience($id)
    {
        if ($this->session->trainer == 2 || $this->session->cluber == 2){
            $exp = $this->CabinetModel->dancerExpHtml($id);
            $this->load->view('trainer/experience',$exp);
        }
        else {
            $this->load->view('errors/error_access');
        }
    }

    public function admincompetition($id){
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $status = $this->CabinetModel->getCompStatus($id);
            $comp_list = $this->AjaxModel->AdminCompList($id, 'admin');
            //$list = $this->AjaxModel->getCompList2($id, 'admin');
            $list = $this->AjaxModel->getAdminCompList($id);
            $files = $this->AjaxModel->getCSVlist($list, $id);
            $data=array(
                'comp_id'=>$id,
                'comp_list'=>$comp_list,
                'files'=>$files,
                'status'=>$status,
            );
            $this->load->view('admin/competition',$data);
        }
    }

    public function numbers($comp_id)
    {
        if ($this->session->admin == 2 || $this->session->organizer == 2){
            $res= $this->CabinetModel->getNumbers($comp_id);
            $res['comp_id']=$comp_id;
            $this->load->view('admin/numbers',$res);
        }
        else {
            $this->load->view('errors/error_access');
        }
    }

    public function orgnumbers($comp_id)
    {
         if ($this->session->organizer == 2){
            $res= $this->CabinetModel->getNumbers($comp_id);
            $res['comp_id'] = $comp_id;
            $this->load->view('organizer/numbers',$res);
        }
        else {
            $this->load->view('errors/error_access');
        }
    }

    public function uploadResults($comp_id)
    {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
        $file=$this->AjaxModel->getResultCsv($comp_id, 'admin');
        $list=$this->AjaxModel->getResultHtml($comp_id, 'admin');
        $this->load->view('admin/upload',['comp_id'=>$comp_id,'file'=>$file,'list'=>$list]);
        }
    }

    public function clubcompetitions(){
        if ($this->session->cluber != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $competitions=$this->CabinetModel->htmlCompetitions('cluber');
            $data=array(
                'competitions'=>$competitions
            );
            $this->load->view('cluber/competitions',$data);
        }
    }

    public function clubeaddtocomp($comp_id)
    {
        if ($this->session->cluber != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $club_id = $this->AjaxModel->getClubId($this->session->id);
            $comp_list=$this->AjaxModel->getCompListHtml($comp_id, 'cluber', $club_id);
            $dancers = $this->CabinetModel->allDancersToComp('cluber');
            //$list = $this->AjaxModel->getCompList2($comp_id, 'cluber', $club_id);
            $files = $this->AjaxModel->genCluberCsvFiles($comp_id);
            $data=array(
                'dancers'=>$dancers,
                'comp_id'=>$comp_id,
                'comp_list'=>$comp_list,
                'files'=>$files
            );
            $this->load->view('cluber/adddancerstocomp',$data);
        }
    }

    public function clubercompinfo($comp_id)
    {
        if ($this->session->cluber != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $club_id = $this->AjaxModel->getClubId($this->session->id);
            $comp_list=$this->AjaxModel->getCompListHtml($comp_id, 'cluber', $club_id);
            //$list = $this->AjaxModel->getCompList2($comp_id, 'cluber', $club_id);
            //$files = $this->AjaxModel->getCSVlist($list, $comp_id, 'cluber');
            $files = $this->AjaxModel->genCluberCsvFiles($comp_id);
            $data=array(
                'comp_id'=>$comp_id,
                'comp_list'=>$comp_list,
                'files'=>$files
            );
            $this->load->view('cluber/clubercompinfo',$data);
        }
    }

    public function yearpay()
    {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $data = $this->AjaxModel->getYearPay2('all', 20, 1);
            $this->load->view('admin/yearpay',['list'=>$data['list'],'pagg'=>$data['pagg']]);
        }

    }

    public function orgcompetition($comp_id){
        if ($this->session->id >0){
            $val = $this->CabinetModel->isOrgComp($comp_id, $this->session->id);
            if (!$val){
                $this->load->view('errors/error_access');
            }
            else {
                $comp_list=$this->AjaxModel->AdminCompList($comp_id, 'admin');
                $list = $this->AjaxModel->getAdminCompList($comp_id);
                $files = $this->AjaxModel->getCSVlist($list, $comp_id);
                $data=[
                    'comp_id'=>$comp_id,
                    'comp_list'=>$comp_list,
                    'files'=>$files,
                        ];
                $this->load->view('organizer/competition',$data);
            }
        } else{
            $this->load->view('errors/error_access');
        }
    }

    public function comppays($comp_id)
    {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $comp_list=$this->AjaxModel->getCompListHtml($comp_id, 'admin');
            $pay_list=$this->CabinetModel->getPayListHtml($comp_id);
            $data=array(
                'comp_id'=>$comp_id,
                'comp_list'=>$comp_list,
                'pay_list'=>$pay_list
            );
            $this->load->view('admin/comppays',$data);
        }
    }

    public function compcontacts($comp_id)
    {
        $cont= $this->CabinetModel->getCompContacts($comp_id);
        $this->load->view('admin/contacts',$cont);
    }

    public function admincompcontacts($comp_id)
    {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
        $cont= $this->CabinetModel->getCompContacts($comp_id);
        $this->load->view('admin/contacts',$cont);
        }
    }

    public function orgcompcontacts($comp_id)
    {
        if ($this->session->organizer != 2){
            $this->load->view('errors/error_access');
        }
        else {
        $cont= $this->CabinetModel->getCompContacts($comp_id);
        $this->load->view('organizer/contacts',$cont);
        }
    }

    /*public function test()
    {
    echo "TEST <br>";
    $rows=6;
    $in_page=5;
        $tall=$rows%$in_page;
    $rows-=$tall;
    $pages=round($rows/$in_page);
    if ($tall>0) $pages+=1;
    echo 'pages= '.$pages.'<br>';
    }*/

    public function statistic() {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $ways=$this->CabinetModel->selectWays();
            $this->load->view('admin/statistic',['ways' => $ways]);
        }
    }

    public function adddancers($comp_id){
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $clubes=$this->CabinetModel->selectAllClubes();
            $data=[
                'comp_id'=>$comp_id,
                'clubes'=>$clubes
            ];
            //var_dump($clubes);
            $this->load->view('admin/adddancers',$data);
        }
    }

    public function adminAddToComp(){
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {

            $trainer_id = $_POST['trainer_id'];
            $comp_id = $_POST['comp_id'];
            $comp_list=$this->AjaxModel->getCompListHtml($comp_id, 'trainer', $trainer_id);
            $dancers = $this->CabinetModel->allDancersToComp('trainer', $trainer_id);
            $data=array(
                'dancers'=>$dancers,
                'comp_id'=>$comp_id,
                'comp_list'=>$comp_list
            );
            $this->load->view('admin/adddancerstocomp',$data);
        }
    }

    public function adminarchive()
    {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $statuses = $this->CabinetModel->selectStatuses();
            $regions = $this->CabinetModel->regions_html();
            $ways=$this->CabinetModel->selectWays();
            $competitions = $this->CabinetModel->htmlAdminArchive();
            $data=array(
                'regions'=>$regions,
                'ways'=>$ways,
                'competitions'=>$competitions,
                'statuses'=>$statuses,
            );
            $this->load->view('admin/archive', $data);
        }
    }

    public function recoverCompetition($comp_id)
    {
        if ($this->session->admin != 2){
            $this->load->view('errors/error_access');
        }
        else {
            $this->AjaxModel->recoverCompetition(2);
            $this->admincompetition($comp_id);
        }
    }
}
