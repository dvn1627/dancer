<?php
class CabinetModel extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

        public function getUsers($next){
		$sel = 'select * from users'
		 	. ' where deleted_at is null'
			. ' LIMIT '.$next.',20';
		$query = $this->db->query($sel);
		$users=$query->result_array();
		return $users;
	}

	public function paginate($table,$path='')
	{

		$q = $this->db->query('select count(id) as count from '.$table);
		$count=$q->result()[0]->count;
		$config['base_url'] = base_url().$path;
		$config['total_rows'] = $count;
		$config['per_page'] = 20;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li  class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		return $config;
	}

	public function is_organizer($id)
	{
		$q=$this->db->query('select id from organizers where user_id='.$id);
		if ($q->result()){
			return true;
		}
		else{
			return false;
		}
	}

    public function is_cluber($id)
    {
        $q = $this->db->query('select id from clubers where user_id='.$id);
        if ($q->result()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function is_trainer($id)
    {
        $q = $this->db->query('select id from trainers where user_id='.$id);
        if ($q->result()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function is_dancer($id)
    {
        $q = $this->db->query('select id from dancers where user_id='.$id);
        if ($q->result()) {
            return true;
        }
        else {
            return false;
        }
    }


	public function get_regions()
	{
		$q=$this->db->query('select * from regions');
		return $q->result_array();
	}

	public function regions_html()
	{
		$regions=$this->get_regions();
		$html="";
		foreach ($regions as $region) {
			$html.='<option value="'.$region['id'].'">'.$region['region'].'</option>';
		}
		return $html;
	}

	public function getCities($region)
	{
		$q=$this->db->query('select id,city from cities where region_id='.$region.' and deleted_at is null');
		return $q->result_array();
	}

	public function citiesHtml($region)
	{
		$cities=$this->getCities($region);
		$html='<option value="0">выберите город...</option>';
		foreach ($cities as $city) {
			$html.='<option value="'.$city['id'].'">'.$city['city'].'</option>';
		}
		return $html;
	}

	public function getClubes($city)
	{
		$q=$this->db->query('select id,title from clubers where city_id='.$city);
		return $q->result_array();
	}

	public function clubesHtml($city)
	{
		$clubes=$this->getClubes($city);
		$html='<option value="0">выберите клуб...</option>';
		foreach ($clubes as $club) {
			$html.='<option value="'.$club['id'].'">'.$club['title'].'</option>';
		}
		return $html;
	}

    public function getBelly()
    {
        $q=$this->db->query('select * from bellydance');
        return $q->result_array();
	}

    public function bellyHtml()
    {
        $belly=$this->getBelly();
        $html='';
        foreach ($belly as $b) {
            $html.='<option value="'.$b['id'].'">'.$b['name'].'</option>';
        }
        return $html;
    }

    public function getTrainers($club)
    {
	$q=$this->db->query('select t.id,u.last_name,u.first_name,u.father_name
	from trainers t, users u
	where t.user_id=u.id and club_id='.$club);
        return $q->result_array();
    }

    public function getOrgId($user_id) {
        $q = $this->db->query('select id from organizers where user_id='.$user_id);
        if ($res = $q->result_array()) {
            return $res[0]['id'];
        }
        else {
            return false;
        }
    }

    public function trainersHtml($club)
    {
        $trainers=$this->getTrainers($club);
	$html='<option value="0">выберите тренера...</option>';
	foreach ($trainers as $trainer) {
            $name=$trainer['last_name'].' '.$trainer['first_name'].' '.$trainer['father_name'];
            $html.='<option value="'.$trainer['id'].'">'.$name.'</option>';
	}
	return $html;
    }

    public function getWays()
    {
        $q=$this->db->query('select * from ways where deleted=0');
        return $q->result_array();
    }

    public function htmlWays()
    {
        $data=$this->getways();
        $html="";
        foreach ($data as $d) {
            $html.='<tr>';
            $html.='<td class="hidden">'.$d['id'].'</td>';
            $html.='<td>'.$d['way'].'</td>';
            $html.='<td><button class="btn btn-warning btn-sm edit" id="e'.$d['id']
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
            $html.='<button class="btn btn-danger btn-sm del" id="d'.$d['id'].'">delete</button></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function selectWays()
    {
        $data=$this->getways();
        $html='<option value="0">Выберите направление</option>';
        foreach ($data as $d) {
            $html.='<option value="'.$d['id'].'">';
            $html.=$d['way'].'</option>';
        }
        return $html;
    }

    public function getCounts()
    {
        $q=$this->db->query('select * from cat_count where deleted=0');
        return $q->result_array();
    }

    public function htmlCounts()
    {
        $data=$this->getCounts();
        $html="";
        foreach ($data as $d) {
            $html.='<tr>';
            $html.='<td class="hidden">'.$d['id'].'</td>';
            $html.='<td>'.$d['name'].'</td>';
            $html.='<td>'.$d['min_count'].'</td>';
            $html.='<td>'.$d['max_count'].'</td>';
            $html.='<td><button class="btn btn-warning btn-sm edit" id="e'.$d['id']
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
            $html.='<button class="btn btn-danger btn-sm del" id="d'.$d['id'].'">delete</button></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function getAges()
    {
        $q=$this->db->query('select * from cat_age where deleted=0');
        return $q->result_array();
    }

    public function htmlAges()
    {
        $data=$this->getAges();
        $html="";
        foreach ($data as $d) {
            $html.='<tr>';
            $html.='<td class="hidden">'.$d['id'].'</td>';
            $html.='<td>'.$d['name'].'</td>';
            $html.='<td>'.$d['min_age'].'</td>';
            $html.='<td>'.$d['max_age'].'</td>';
            switch ($d['dancers_count']) {
            case 0:
                $html.='<td>все</td>';
                break;
            case 1:
                $html.='<td>соло</td>';
                break;
            case 2:
                $html.='<td>два и более</td>';
                break;
            }
            $html.='<td><button class="btn btn-warning btn-sm edit" id="e'.$d['id']
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
            $html.='<button class="btn btn-danger btn-sm del" id="d'.$d['id'].'">delete</button></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function selectAges()
    {
        $data=$this->getAges();
        $html="";
        foreach ($data as $d) {
            $html.='<option value='.$d['id'].'>'.$d['name'].'</option>';
        }
        return $html;
    }

    public function trainerContact()
    {
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.phone '
                . 'from users u, trainers t '
                . 'where u.id=t.user_id and t.user_id='.$this->session->id);
        $t=$q->result_array();
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.phone, c.title'
                . ' from users u, trainers t , clubers c'
                . ' where u.id=c.user_id and t.club_id=c.id and t.user_id='.$this->session->id);
        $c=$q->result_array();
        $data=array(
            'trainer_name'=>$t[0]['last_name'].' '.$t[0]['first_name'].' '.$t[0]['father_name'],
            'trainer_email'=>$t[0]['email'],
            'trainer_phone'=>$t[0]['phone'],
            'club_name'=>$c[0]['last_name'].' '.$c[0]['first_name'].' '.$c[0]['father_name'],
            'club_email'=>$c[0]['email'],
            'club_phone'=>$c[0]['phone'],
            'club_title'=>$c[0]['title'],
        );
        return $data;
    }

    public function htmlTrainerDancers($trainer_id) {
        $q = $this->db->query('select u.first_name, u.last_name, u.phone, u.email, u.dancer,'
                . ' d.id, d.birthdate, d.user_id'
                . ' from users u, dancers d'
                . ' where u.deleted_at is null and u.id=d.user_id and d.trainer_id='
                . '(select id from trainers where user_id='.$trainer_id.')');
        $html='';
        foreach ($q->result() as $r)
        {
            $html .= '<tr>';
            $html .= '<td class="hidden">'.$r->id.'</td>';
            $html .= '<td>'.$r->last_name.' '.$r->first_name.'</td>';
            $birth =  strtotime($r->birthdate);
            $ytime = time() - $birth;
            $year = ($ytime - $ytime % 31556926) / 31556926;
            $html .= '<td>'.$year.'</td>';
            if ($r->dancer == 0) $html .= '<td> нет </td>';
            if ($r->dancer == 1) $html .= '<td> запрошен </td>';
            if ($r->dancer == 2) $html .= '<td> активный </td>';
            if ($r->dancer == 3) $html .= '<td> заблокирован </td>';
            $html .= '<td>'.$r->email.' '.$r->phone.'</td>';
            $html.='<td><button class="btn btn-info btn-sm info" id="i'.$r->id
                    .'" data-toggle="modal" data-target="#infomodal">info</button> ';
            $html.='<button class="btn btn-warning btn-sm edit" id="e'.$r->id
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
            $html.='<a href="../cabinet/experience/'.$r->id.'" class="btn btn-default btn-sm">опыт</a>';
            if ($r->dancer != 2 ){
                $html.=' <button class="btn btn-success btn-sm activate" id="a'.
                        $r->id.'">activate</button></td>';
            }
            if ($r->dancer == 1 ||  $r->dancer == 2){
                $html.=' <button class="btn btn-warning btn-sm deactivate" id="d'.
                        $r->id.'">delactivate</button>';
            }
			$html.=' <button class="btn btn-danger btn-sm del_but"  data-toggle="modal" data-target="#deleteModal" >DELETE</button><input class="hidden" value="'.$r->user_id.'" name="user_id"></td>';
			//$html.=' <button class="btn btn-danger btn-sm del_but">DELETE</button><input class="hidden" value="'.$r->user_id.'" name="user_id"></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    public function htmlTrainerDancers2($trainer_id) {
        $q = $this->db->query('select u.first_name, u.last_name, u.phone, u.email, u.dancer,'
                . ' d.id, d.birthdate, d.user_id'
                . ' from users u, dancers d'
                . ' where u.deleted_at is null and u.id=d.user_id and d.trainer_id='
                . '(select id from trainers where user_id='.$trainer_id.')');
        $html='';
        foreach ($q->result() as $r)
        {
            $html .= '<tr>';
            $html .= '<td class="hidden">'.$r->id.'</td>';
            $html .= '<td>'.$r->last_name.' '.$r->first_name.'</td>';
            $birth =  strtotime($r->birthdate);
            $ytime = time() - $birth;
            $year = ($ytime - $ytime % 31556926) / 31556926;
            $html .= '<td>'.$year.'</td>';
            if ($r->dancer == 0) $html .= '<td> нет </td>';
            if ($r->dancer == 1) $html .= '<td> запрошен </td>';
            if ($r->dancer == 2) $html .= '<td> активный </td>';
            if ($r->dancer == 3) $html .= '<td> заблокирован </td>';
            $html .= '<td>'.$r->email.' '.$r->phone.'</td>';
            $html.='<td><button class="btn btn-info btn-sm info" id="i'.$r->id
                    .'" data-toggle="modal" data-target="#infomodal">info</button> ';
            $html.='<button class="btn btn-warning btn-sm edit" id="e'.$r->id
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
            $html.='<a href="../experience/'.$r->id.'" class="btn btn-default btn-sm">опыт</a>';
            if ($r->dancer != 2 ){
                $html.=' <button class="btn btn-success btn-sm activate" id="a'.
                        $r->id.'">activate</button></td>';
            }
            if ($r->dancer == 1 ||  $r->dancer == 2){
                $html.=' <button class="btn btn-warning btn-sm deactivate" id="d'.
                        $r->id.'">delactivate</button> ';
            }
			$html.=' <button class="btn btn-danger btn-sm del_but"  data-toggle="modal" data-target="#deleteModal" >DELETE</button><input class="hidden" value="'.$r->user_id.'" name="user_id"></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    public function dancerContact()
    {
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.phone '
                . 'from users u, trainers t, dancers d '
                . 'where u.id=t.user_id and d.trainer_id=t.id and d.user_id='.$this->session->id);
        $t=$q->result_array();
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.phone, c.title'
                . ' from users u, trainers t , clubers c, dancers d'
                . ' where u.id=c.user_id and t.club_id=c.id and d.trainer_id=t.id and d.user_id='.$this->session->id);
        $c=$q->result_array();
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.phone, '
                . 'd.birthdate, d.id'
                . ' from users u, dancers d'
                . ' where u.id=d.user_id and d.user_id='.$this->session->id);
        $d=$q->result_array();
        //return $this->session->id;
        $data=array(
            'trainer_name'=>$t[0]['last_name'].' '.$t[0]['first_name'].' '.$t[0]['father_name'],
            'trainer_email'=>$t[0]['email'],
            'trainer_phone'=>$t[0]['phone'],
            'club_name'=>$c[0]['last_name'].' '.$c[0]['first_name'].' '.$c[0]['father_name'],
            'club_email'=>$c[0]['email'],
            'club_phone'=>$c[0]['phone'],
            'club_title'=>$c[0]['title'],
            'dancer_id'=>$d[0]['id'],
            'dancer_name'=>$d[0]['last_name'].' '.$c[0]['first_name'].' '.$c[0]['father_name'],
            'dancer_email'=>$d[0]['email'],
            'dancer_phone'=>$d[0]['phone'],
            'dancer_birthdate'=>$d[0]['birthdate'],
        );
        return $data;
    }

    public function htmlCluberTrainers($cluber_id) {
        $q = $this->db->query('select u.first_name, u.last_name, u.father_name, u.phone, u.email, u.trainer, t.id'
                . ' from users u, trainers t'
                . ' where u.id=t.user_id and t.club_id='
                . '(select id from clubers where user_id='.$cluber_id.')');
        $html='';
        foreach ($q->result() as $r)
        {
            $html .= '<tr>';
            $html .= '<td class="hidden">'.$r->id.'</td>';
            $html .= '<td>'.$r->last_name.' '.$r->first_name.' '.$r->father_name.'</td>';
            if ($r->trainer == 0) $html .= '<td> нет_ </td>';
            if ($r->trainer == 1) $html .= '<td> запрошен </td>';
            if ($r->trainer == 2) $html .= '<td> активный </td>';
            if ($r->trainer == 3) $html .= '<td> заблокирован </td>';
            $html .= '<td>'.$r->email.' '.$r->phone.'</td>';
            $html.='<td><button class="btn btn-info btn-sm info" id="i'.$r->id
                    .'" data-toggle="modal" data-target="#infomodal">info</button> ';
            $html.='<button class="btn btn-warning btn-sm edit" id="e'.$r->id
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
            if ($r->trainer != 2 ){
                $html.='<button class="btn btn-success btn-sm activate" id="a'.
                        $r->id.'">activate</button>';
            }
            if ($r->trainer == 1 ||  $r->trainer == 2){
                $html.='<button class="btn btn-danger btn-sm deactivate" id="d'.
                        $r->id.'">delactivate</button>';
            }
            $html.=' <a href="../cabinet/trainerdancers/'.$this->user('trainer',$r->id).'" class="btn btn-default btn-sm dancers" id="t'.$r->id.'">танцоры</a></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    public function user($role, $role_id){
        switch ($role){
            case 'dancer':
                $table='dancers';
                break;
            case 'trainer':
                $table='trainers';
                break;
            case 'cluber':
                $table='clubers';
                break;
            case 'organizer':
                $table='organizers';
                break;
        }
        $q=$this->db->query('select user_id from '.$table.' where id='.$role_id);
        $res=$q->result_array();
        return $res[0]['user_id'];
    }

    public function cluberContact()
    {
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.phone, c.title'
                . ' from users u, clubers c'
                . ' where u.id=c.user_id and c.user_id='.$this->session->id);
        $c=$q->result_array();
        $data=array(
            'club_name'=>$c[0]['last_name'].' '.$c[0]['first_name'].' '.$c[0]['father_name'],
            'club_email'=>$c[0]['email'],
            'club_phone'=>$c[0]['phone'],
            'club_title'=>$c[0]['title'],
        );
        return $data;
    }

    public function showUser($id){
        $q=$this->db->query('select first_name, last_name, father_name, email, password, phone, id'
                . ' from users'
                . ' where id='.$id);
        $res=$q->result();
        return $res[0];
    }

    public function htmlCompetitions($role)
    {
        $q = $this->db->query('select c.name, c.id, ci.city, c.org_id,'
                . ' c.date_reg_open, c.date_reg_close, c.date_open, c.date_close, s.status'
                . ' from competitions c, statuses s, cities ci'
                . ' where c.city_id=ci.id and c.status_id=s.id and c.date_close>now()');
        $html='';
        foreach ($q->result() as $r)
        {
            $html .= '<tr>';
            $html .= '<td class="hidden">'.$r->id.'</td>';
            $html .= '<td>'.$r->name.'</td>';
            $html .= '<td>'.$r->city.'</td>';
            $html .= '<td>с '.$r->date_reg_open.' по '.$r->date_reg_close.'</td>';
            $html .= '<td>с '.$r->date_open.' по '.$r->date_close.'</td>';
            $html .= '<td>'.$r->status.'</td>';
            $html.='<td><button class="btn btn-info btn-sm info" id="i'.$r->id
                    .'" data-toggle="modal" data-target="#infomodal">info</button> ';

            if ($role=="admin"){
            $html.='<button class="btn btn-warning btn-sm edit" id="e'.$r->id
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
                $html.=' <a href="../cabinet/admincompetition/'.$r->id.'" class="btn btn-default btn-sm comp" id="c'.$r->id.'">управление</a>';
            }
            if ($role=="trainer"){
                if (strtotime($r->date_reg_open)<time() && strtotime($r->date_reg_close)>time() && $r->status=='ON'){
                    $html.=' <a href="../cabinet/traineraddtocomp/'.$r->id.'" class="btn btn-success btn-sm comp" id="c'.$r->id.'">регистрация участников</a>';
                }else{
                    $html.=' <a href="../cabinet/trainercompinfo/'.$r->id.'" class="btn btn-default btn-sm comp" id="c'.$r->id.'">подробнее</a>';
                }
            }
            if ($role=="cluber"){
                if (strtotime($r->date_reg_open)<time() && strtotime($r->date_reg_close)>time() && $r->status=='ON'){
                    $html.=' <a href="../cabinet/clubeaddtocomp/'.$r->id.'" class="btn btn-success btn-sm comp" id="c'.$r->id.'">регистрация участников</a>';
                }else{
                    $html.=' <a href="../cabinet/clubercompinfo/'.$r->id.'" class="btn btn-default btn-sm comp" id="c'.$r->id.'">подробнее</a>';
                }
            }
            if ($role=='organizer'){
                $org = $this->getOrgId($this->session->id);
                if ($org == $r->org_id){
                    $html.=' <a href="../orgcompetition/'.$r->id.'" class="btn btn-default btn-sm comp" id="c'.$r->id.'">управление</a>';
                }
            }
            $html .= '</td></tr>';
        }
        return $html;
    }

    public function selectStatuses()
    {
        $q = $this->db->query('select * from statuses');
        $html='';
        $row = $q->result_array();
        foreach ($row as $r){
            $html .= '<option value="'.$r['id'].'">'.$r['status'].'</option>';
        }
        return $html;
    }

    public function allDancersToComp($role, $id=0)
    {
        if ($id>0 && $role == 'trainer'){
            $select='select u.last_name, u.first_name, d.birthdate, d.id'
                    . ' from users u, dancers d'
                    . ' where u.deleted_at is null and u.dancer=2 and d.user_id=u.id and trainer_id='.$id
					. ' and u.dancer=2';
            } else{
            if ($role == 'trainer'){
                $select='select u.last_name, u.first_name, d.birthdate, d.id'
                        . ' from users u, dancers d'
                        . ' where u.deleted_at is null and u.dancer=2 and d.user_id=u.id and trainer_id=(select id from trainers where user_id='.$this->session->id.')';
            }
            if ($role == 'cluber'){
                $select='select u.last_name, u.first_name, d.birthdate, d.id'
                        . ' from users u, dancers d, trainers t'
                        . ' where u.deleted_at is null and u.dancer=2 and d.user_id=u.id and d.trainer_id=t.id and'
                        . ' club_id=(select id from clubers where user_id='.$this->session->id.')';
                }
        }
        $q = $this->db->query($select);
        $row = $q->result_array();
        $html='';
        foreach ($row as $r){

            $html .= '<tr>';
            $html .='<td><input type="checkbox" name="dancer[]" value='.$r['id'].'>'
                    .$r['last_name'].' '.$r['first_name'].'</td>';
            $html .= '</tr>';
        }
        return $html;
    }

    public function getCatList($data)
    {
        if (!isset($data['dancer'])) return false;
        $dancers = $data['dancer'];
        $comp_id = $data['comp_id'];
        //находим количесво в группе
        $d_count=count($dancers);
        //получаем массив возрастов
        $ages = array();
        $q = $this->db->query('select birthdate from dancers where id in ('.implode(',', $dancers).')');
        $res = $q->result_array();
        foreach ($res as $r){
			//21.11.2017
            // $birth =  strtotime($r['birthdate']);
            // $ytime = time() - $birth;
            // $ages[] = ($ytime - $ytime % 31556926) / 31556926;
			$birth = substr($r['birthdate'], 0, 4);
			$ytime = date('Y', time());
			$ages[] = $ytime - $birth;
			//21.11.2017
        }
        //получаем массив стилей
        $s_count = ($d_count > 1) ? 2: 1;
        $q = $this->db->query('select id, style from styles'
                . ' where dancers_count in(0,'.$s_count.')'
                . ' and way_id=(select way_id from competitions where id='.$comp_id.') and deleted=0');
        $styles = $q->result_array();
        //получаем массив возрастных групп(категорий)
        if ($d_count>1){
            $dd=2;
        } else{
            $dd=1;
        }
        $q = $this->db->query('select id, name, min_age, max_age'
                . ' from cat_age'
                . ' where dancers_count in (0,'.$dd.') and deleted=0');
        $res = $q->result_array();
        $age_cat = array();
        foreach ($res as $r){
            $f = TRUE;
            foreach($ages as $age){
                if ($age > $r['max_age'] || $age < $r['min_age']){
                    $f = FALSE;
                }
            }
            if ($f) $age_cat[] = $r;
        }
        //получаем массив категорий по количеству
        $q = $this->db->query('select id, name from cat_count'
                . ' where min_count<='.$d_count.' and max_count>='.$d_count.' and deleted=0');
        $count_cat = $q->result_array();
        //получаем список лиг
        if ($d_count > 1){
            $q = $this->db->query('select l.id, l.name, l.number'
                    . ' from ligs l, dancers d, experience e'
                    . ' where l.deleted=0 and l.id in (select lig_id from experience'
                    . ' where e.dancer_id in ('.implode(',', $dancers).'))'
                    . ' and l.way_id=(select way_id from competitions where id='.$comp_id.')'
                    . ' and e.lig_id=l.id and e.dancer_id=d.id');
            $dan_lig= $q->result_array();
            $dancers_count=count($dancers);
            $deb=$dancers_count-count($dan_lig);
			$all_deb = true;
            foreach ($dan_lig as $dl){
                if ($dl['name']=="Дебют"){
                    $deb++;
                } else{
					$all_deb = false;//21.11.2017
				}
            }
			//21.11.2017
            // if ($deb/$dancers_count>0.1){
            //     $lig_name="Дебют";
            // } else {
            //     $lig_name="Открытая лига";
            // }
			if ($all_deb){
				$lig_name="Дебют";
            } else {
                $lig_name="Открытая лига";
            }
			//21.11.2017
            $q = $this->db->query('select id, name from ligs'
                    . ' where name="'.$lig_name.'"'
                    . ' and way_id=(select way_id from competitions where id='.$comp_id.') and deleted=0');
            $ligs = $q->result_array();
        }
        else {//соло
            $ages_str='';
            $i=FALSE;
            foreach($age_cat as $age){
                $ages_str.= ($i) ? ',':'';
                $i=TRUE;
                $ages_str.= $age['id'];
            }
			if ($ages_str == ''){
				$ages_str = '0';
			}
            $q = $this->db->query('select DISTINCT l.id, l.name'
                    . ' from ligs l, show_ligs s'
                    . ' where s.lig_id=l.id and s.age_id in ('.$ages_str.')'
                    . ' and l.way_id=(select way_id from competitions where id='.$comp_id.') and deleted=0');
            $ligs = $q->result_array();
            $q = $this->db->query('select DISTINCT l.id, l.name, l.number'
                    . ' from ligs l, dancers d, experience e'
                    . ' where l.deleted=0 and l.id in (select lig_id from experience'
                    . ' where dancer_id='.$dancers[0].')'
                    . ' and l.way_id=(select way_id from competitions where id='.$comp_id.')'
                    . ' and e.lig_id=l.id and e.dancer_id=d.id');
            $dan_lig= $q->result_array();
            if (count($dan_lig) == 0){
                $q= $this->db->query('select id, name, number from ligs'
                    . ' where number=1 and way_id=(select way_id from competitions where id='.$comp_id.')');
                $dan_lig = $q->result_array();
                }
            /*if ($dan_lig[0]['name']!='Профессионалы'){
                $q = $this->db->query('select id, name, number from ligs'
                    . ' where number='.($dan_lig[0]['number']+1).' and way_id=(select way_id from competitions where id='.$comp_id.')');
                $res = $q->result_array();
                $dan_lig[1]=$res[0];
            }*/
			$ligs = $dan_lig;
        }
        //генерируем суммарные категории
        $html='';
        $i=1;
        foreach ($styles as $style){
            foreach ($age_cat as $age){
                foreach ($count_cat as $count){
                    foreach ($ligs as $lig){
                        $html.='<tr><td>';
                        $html.='<form>';
                        $html.='<input type="hidden" name="style_id" value="'.$style['id'].'">';
                        $html.='<input type="hidden" name="age_id" value="'.$age['id'].'">';
                        $html.='<input type="hidden" name="count_id" value="'.$count['id'].'">';
                        $html.='<input type="hidden" name="lig_id" value="'.$lig['id'].'">';
                        $html.='<input type="checkbox" id="cat'.$i.'" class="sum_cat">';
                        $html.=$i.' '.$style['style'].' '.$age['name'].' '.$count['name'].' '.$lig['name'];
                        $html.='</form>';
                        $html.='</td></tr>';
                        $i++;
                    }
                }
            }
        }

        return $html;
    }

    public function getDancersList($dancers){
        $q= $this->db->query('select u.first_name, u.last_name, d.birthdate, d.id'
                . ' from dancers as d '
                . ' right join users as u '
                . ' on u.id=d.user_id'
                . ' where d.id in ('.implode(',', $dancers).')');
        $res = $q->result_array();
        $html='';
        foreach ($res as $r){
            $html.='<tr>';
            $html.='<input type="hidden" name="dancer[]" value='.$r['id'].'>';
            $html.='<td>'.$r['last_name'].' '.$r['first_name'].'</td>';
            $html.='<td>'.$r['birthdate'].'</td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function dancerExpHtml($dancer_id){
        $q= $this->db->query('select u.first_name, u.last_name, u.father_name, d.birthdate, u.phone, u.email, d.id'
                . ' from dancers as d '
                . ' right join users as u '
                . ' on u.id=d.user_id'
                . ' where d.id ='.$dancer_id);
        $res = $q->result_array();
        $dancer=$res[0];
        $html='';
        $q= $this->db->query('select ways.way, ways.id, experience.points, ligs.name'
                . ' from ways'
                . ' left join experience on ways.id=experience.way_id'
                . ' and dancer_id='.$dancer_id.''
                . ' left join ligs on experience.lig_id=ligs.id'
                . ' where ways.deleted=0');
        $res = $q->result_array();
        foreach ($res as $r){
            $html.='<tr>';
            $html.='<td>'.$r['way'].'</td>';
            if (is_null($r['name'])){
                $html.='<td>нет опыта</td><td><button class="btn btn-success add_exp" id="w'.$r['id']
                        .'" data-toggle="modal" data-target="#addmodal">добавить</button>';
            }else{
                $html.='<td>'.$r['name'].'</td>';
                $html.='<td>'.$r['points'].'</td>';
            }
            $html.='</tr>';
        }
        $data=array(
            'dancer'=>$dancer,
            'exp'=>$html
        );
        return $data;
    }

    public function getPayListHtml($comp_id)
    {
        $q = $this->db->query('select DISTINCT count_id, lig_id'
                . ' from comp_list'
                . ' where comp_id='.$comp_id);
        $comp_list = $q->result_array();
        $q = $this->db->query('select count_id, lig_id'
                . ' from pays'
                . ' where comp_id='.$comp_id);
        $pay_list = $q->result_array();
        $insert=array();
        foreach ($comp_list as $c){
            $find = FALSE;
            foreach ($pay_list as $p){
                if ($c['count_id'] == $p['count_id'] && $c['lig_id'] == $p['lig_id']){
                    $find = TRUE;
                }
            }
            if ($find == FALSE){
                $in_arr = FALSE;
                foreach ($insert as $ins){
                    if ($ins['count_id'] == $c['count_id'] && $ins['lig_id'] == $c['lig_id'] && $ins['comp_id'] == $c['comp_id']){
                        $in_arr = TRUE;
                    }
                }
                if ($in_arr == FALSE){
                    $insert[]=[
                    'count_id'=>$c['count_id'],
                    'lig_id'=>$c['lig_id'],
                    'comp_id'=>$comp_id
                        ];
                }
            }
        }
        if (count($insert) > 0) $q= $this->db->insert_batch('pays',$insert);
        $q = $this->db->query('select p.id, l.name as lig, cc.name,'
                . ' p.pay_iude, p.pay_other, p.pay_not'
                . ' from pays p, cat_count cc, ligs l'
                . ' where p.lig_id=l.id and p.count_id=cc.id and p.comp_id='.$comp_id);
        $res= $q->result_array();
        $html='';
        foreach ($res as $r){
            $html.='<tr>';
            $html.='<td>'.$r['name'].'</td>';
            $html.='<td>'.$r['lig'].'</td>';
            $html.='<td><input type="hidden" name="id[]" value='.$r['id'].'>';
            $html.='<input type="text" name="pay_iude[]" value='.$r['pay_iude'].' class="col-xs-5"></td>';
            $html.='<td><input type="text" name="pay_other[]" value='.$r['pay_other'].' class="col-xs-5"></td>';
            $html.='<td><input type="text" name="pay_not[]" value='.$r['pay_not'].' class="col-xs-5"></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function setNumbers($comp_id)
    {
        $q = $this->db->query('select DISTINCT t.club_id'
                . ' from comp_list cl, dancers d, trainers t'
                . ' where cl.dancer_id=d.id and d.trainer_id=t.id and cl.comp_id='.$comp_id);
        $clubs = $q->result_array();
        $q = $this->db->query('select DISTINCT '
                . ' cl.dancer_id,'
                . ' t.club_id, cl.count_id'
                . ' from comp_list as cl, cat_count as cc, '
                . ' dancers as d, users as u, trainers as t'
                . ' where cl.count_id=cc.id and d.user_id=u.id'
                . ' and cl.dancer_id=d.id and d.trainer_id=t.id'
                . ' and cc.max_count=1 and cl.comp_id='.$comp_id.''
                . ' order by t.club_id, u.last_name asc');
        $data = $q->result_array();
        $count = count($clubs);
        $rand = [];
        foreach ($clubs as $k => $v){
            do {
                $r =  rand(0, $count-1);
            } while (in_array($r, $rand));
            $rand[] = $r;
        }
        $number=0;
        foreach ($rand as $r) {
            foreach ($data as $d){
                if ($d['club_id'] == $clubs[$r]['club_id']) {
                    $number++;
                    $w=[
                        'dancer_id' => $d['dancer_id'],
                        'count_id' => $d['count_id'],
						'comp_id' => $comp_id,
                        ];
                    $ins=['print_number'=>$number];
                    $this->db->where($w);
                    $this->db->update('comp_list', $ins);
                }
            }
        }
        $q = $this->db->query('select DISTINCT'
                . ' t.club_id, cl.part, d.id'
                . ' from comp_list as cl, cat_count as cc, trainers as t, dancers as d'
                . ' where cl.count_id=cc.id and cl.dancer_id=d.id and d.trainer_id=t.id'
                . ' and cl.comp_id='.$comp_id.' and cc.max_count>1'
                . ' order by t.club_id asc');
        $data = $q->result_array();
		$parts = [];
		$p = 0;
		foreach ($data as $dkey => $dvalue) {
			if (isset($parts[$dvalue['part']])) {
				$parts[$dvalue['part']]['ids'][] = $dvalue['id'];
			} else {
				$parts[$dvalue['part']] = [
					'ids' => [$dvalue['id']],
					'club_id' => $dvalue['club_id'],
					'part' => $dvalue['part'],
				];
			}
		}
		$groups = [];
		$g = 0;
		foreach ($parts as $part) {
			$same = false;
			foreach ($groups as $group) {
				if (count($part['ids']) == count($group['ids'])) {
					$same = true;
					foreach ($part['ids'] as $p) {
						if (!in_array($p, $group['ids'])) {
							$same = false;
						}
					}
				}
			}
			if ($same == false) {
				$groups[] = $part;
			} else {
			}
 		}
		$return = [];
        foreach ($rand as $r){
            foreach ($groups as $group){
                if ($group['club_id'] == $clubs[$r]['club_id']){
                    $number++;
                    $w = [
						'part' => $group['part'],
						'comp_id' => $comp_id,
					];
                    $ins=['print_number'=>$number];
                    $this->db->where($w);
                    $this->db->update('comp_list',$ins);
					$return[] = [
						'part' => $group['part'],
						'comp_id' => $comp_id,
						'print_number'=>$number,
					];
                }
            }
        }
        return true;
    }

    public function getNumbers($comp_id)
    {
        $q = $this->db->query('select DISTINCT u.last_name, u.first_name, l.print_number'
                . ' from comp_list as l, users as u, dancers as d, cat_count as cc'
                . ' where l.dancer_id=d.id and d.user_id=u.id and cc.max_count=1'
                . ' and l.count_id=cc.id and l.comp_id='.$comp_id.''
                . ' order by l.print_number asc');
        $row = $q->result_array();
        $hs='';
        foreach ($row as $r){
            $hs.='<tr>';
            $hs.='<td>'.$r['last_name'].' '.$r['first_name'].'</td>';
            $hs.='<td>'.$r['print_number'].'</td>';
            $hs.='</tr>';
        }
        $q = $this->db->query('select DISTINCT u.last_name, u.first_name, cl.print_number, cl.dancer_id,'
                . ' l.name as lig_name, cc.name as count_name, a.name as age_name, s.style'
                . ' from comp_list as cl, users as u, dancers as d, cat_count as cc,'
                . ' cat_age as a, styles as s, ligs as l'
                . ' where cl.dancer_id=d.id and d.user_id=u.id and cc.max_count>1'
                . ' and cl.lig_id=l.id and cl.count_id=cc.id and cl.age_id=a.id and cl.style_id=s.id'
                . ' and cl.count_id=cc.id and cl.comp_id='.$comp_id.''
                . ' order by cl.print_number asc');
        $row = $q->result_array();
        $hg='';
        $count = count($row);
        for ($i=0;$i<$count;$i++){
            if ($i==0){
                $hg.='<tr>';
                $hg.='<td>'.$row[$i]['style'].' '.$row[$i]['age_name'].' '.$row[$i]['count_name'].' '.$row[$i]['lig_name'].'</td>';
                $hg.='<td>'.$row[$i]['last_name'].' '.$row[$i]['first_name'];
            } elseif ($row[$i]['print_number']!=$row[$i-1]['print_number']){
                $hg.='<tr>';
                $hg.='<td>'.$row[$i]['style'].' '.$row[$i]['age_name'].' '.$row[$i]['count_name'].' '.$row[$i]['lig_name'].'</td>';
                $hg.='<td>'.$row[$i]['last_name'].' '.$row[$i]['first_name'];
            } else{
                $hg.='<br>'.$row[$i]['last_name'].' '.$row[$i]['first_name'];
            }

            if ($i==($count-1)){
                $hg.='</td><td>'.$row[$i]['print_number'].'</td></tr>';
            }elseif ($row[$i]['print_number']!=$row[$i+1]['print_number']){
                $hg.='</td><td>'.$row[$i]['print_number'].'</td></tr>';
            }

        }
        $res=['solo'=>$hs,'group'=>$hg];
        return $res;
    }

    public function isOrgComp($comp_id, $user_id)
    {
        $q = $this->db->query('select id '
                . ' from competitions'
                . ' where id='.$comp_id.' '
                . ' and org_id=(select id from organizers where user_id='.$user_id.')');
        $res= $q->result();
        if (count($res)>0){
            return true;
        }else {
            return false;
        }
    }

    public function getCompContacts($comp_id)
    {
        $q= $this->db->query('select DISTINCT u.first_name, u.last_name, u.father_name,'
                . ' cl.title, ci.city, u.phone, u.email, ci.city'
                . ' from users u, cities ci, clubers cl, comp_list l, dancers d, trainers t'
                . ' where l.dancer_id=d.id and d.trainer_id=t.id and t.club_id=cl.id'
                . ' and cl.city_id=ci.id and cl.user_id=u.id and l.comp_id='.$comp_id);
        $res=$q->result_array();
        $c='';
        foreach ($res as $r){
            $c.='<tr>';
            $c.='<td>'.$r['last_name'].' '.$r['first_name'].' '.$r['father_name'].'</td>';
            $c.='<td>'.$r['title'].'</td>';
            $c.='<td>'.$r['city'].'</td>';
            $c.='<td>'.$r['phone'].'</td>';
            $c.='<td>'.$r['email'].'</td>';
            $c.='</tr>';
        }
        $q= $this->db->query('select DISTINCT u.first_name, u.last_name, u.father_name,'
                . ' cl.title, ci.city, u.phone, u.email, ci.city'
                . ' from users u, cities ci, clubers cl, comp_list l, dancers d, trainers t'
                . ' where l.dancer_id=d.id and d.trainer_id=t.id and t.club_id=cl.id'
                . ' and cl.city_id=ci.id and t.user_id=u.id and l.comp_id='.$comp_id);
        $res=$q->result_array();
        $t='';
        foreach ($res as $r){
            $t.='<tr>';
            $t.='<td>'.$r['last_name'].' '.$r['first_name'].' '.$r['father_name'].'</td>';
            $t.='<td>'.$r['title'].'</td>';
            $t.='<td>'.$r['city'].'</td>';
            $t.='<td>'.$r['phone'].'</td>';
            $t.='<td>'.$r['email'].'</td>';
            $t.='</tr>';
        }
        $cont=[
            'clubers'=>$c,
            'trainers'=>$t,
            'id'=>$comp_id,
        ];
        return $cont;
    }

    public function selectAllClubes()
    {
        $q = $this->db->query('select cl.id, cl.title, ci.city,'
                . ' u.last_name, u.first_name, u.father_name'
                . ' from clubers cl, cities ci, users u'
                . ' where u.deleted_at is null and u.cluber=2 and cl.city_id=ci.id and cl.user_id=u.id');
        $row = $q->result_array();
        $html='<option value="0"> Выберите клуб</option>';
        foreach ($row as $r){
            $html.='<option value='.$r['id'].'>'
                    .$r['title'].' '.$r['last_name'].' '.$r['first_name'].' '.$r['father_name']
                    .' '.$r['city'];
        }
        return $html;
    }

    public function mainCompList()
    {
        $q = $this->db->query('select co.date_open, co.date_close, co.name, co.comment,'
                . ' ci.city, u.first_name, u.last_name, co.id as id'
                . ' from competitions co, cities ci, organizers o, users u'
                . ' where co.city_id=ci.id and co.org_id=o.id and co.date_close>now() and o.user_id=u.id order by date_open asc');
        $data = $q->result_array();
		for ($i = 0; $i < count($data); $i++) {
			$data[$i]['count_dancers'] = $this->getCountDansersFromComp($data[$i]['id']);
			$data[$i]['count_parts'] = $this->getCountPartsFromComp($data[$i]['id']);
		}
        return $data;
    }

	public function getCountDansersFromComp($comp_id)
	{
		$q = $this->db->query('select DISTINCT dancer_id from comp_list where comp_id=' . $comp_id);
		$data = count($q->result_array());
		return $data;
	}

	public function getCountPartsFromComp($comp_id)
	{
		$q = $this->db->query('select DISTINCT part from comp_list where comp_id=' . $comp_id);
		$data = count($q->result_array());
		return $data;
	}

    public function getCompStatus($id)
    {
        $q = $this->db->query('select s.status'
                . ' from competitions c, statuses s'
                . ' where s.id=c.status_id and c.id='.$id);
        $res = $q->result_array();
        return $res[0]['status'];
    }

    public function getCompLink($comp_id, $role='admin')
    {
        $q = $this->db->query('select name from competitions where id='.$comp_id);
        $res = $q->result_array();
        switch ($role){
            case 'admin':
                $l = 'admincompetition';
                break;
            default :
                $l = 'orgcompetition';
                break;
        }
        $link = '<h3><a href="'.base_url().'index.php/cabinet/'.$l.'/'.$comp_id.'">назад к конкурсу "'.$res[0]['name'].'"</a></h3>';
        return $link;
    }
    public function getCompName($comp_id)
    {
        $q = $this->db->query('select c.name, s.status'
							. ' from competitions c, statuses s '
							. ' where c.status_id=s.id'
							. ' and c.id=' . $comp_id);
        $res = $q->result_array();
		$return = $res[0]['name'] . ' <small>' . $res[0]['status'] . '</small>';
        return $return;
    }

	public function htmlAdminArchive()
	{
		$q = $this->db->query('select c.name, c.id, ci.city, c.org_id,'
                . ' c.date_reg_open, c.date_reg_close, c.date_open, c.date_close, s.status'
                . ' from competitions c, statuses s, cities ci'
                . ' where c.city_id=ci.id and c.status_id=s.id and c.date_close < now()');
        $html='';
        foreach ($q->result() as $r)
        {
            $html .= '<tr>';
            $html .= '<td class="hidden">'.$r->id.'</td>';
            $html .= '<td>'.$r->name.'</td>';
            $html .= '<td>'.$r->city.'</td>';
            $html .= '<td>с '.$r->date_reg_open.' по '.$r->date_reg_close.'</td>';
            $html .= '<td>с '.$r->date_open.' по '.$r->date_close.'</td>';
            $html .= '<td>'.$r->status.'</td>';
            $html.='<td><button class="btn btn-info btn-sm info" id="i'.$r->id
                    .'" data-toggle="modal" data-target="#infomodal">info</button> ';

            $html.='<button class="btn btn-warning btn-sm edit" id="e'.$r->id
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
                $html.=' <a href="../cabinet/admincompetition/'.$r->id.'" class="btn btn-default btn-sm comp" id="c'.$r->id.'">управление</a>';
            $html .= '</td></tr>';
        }
        return $html;
	}
}
