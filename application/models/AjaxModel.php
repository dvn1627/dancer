<?php
class AjaxModel extends CI_Model{
    function __construct(){
            parent::__construct();
            $this->load->database();
            $this->load->library('session');
            $this->load->helper('file');
    }

    function getUserInfo($id){
            $query = $this->db->query('select * from users where id='.$id);
            $users=$query->result_array();
            return $users[0];
    }

    function saveUser($user){
            /*$data= array(
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'father_name' => $user['father_name'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'phone' => $user['phone'],
                    'dancer' => $user['dancer'],
                    'trainer' => $user['trainer'],
                    'cluber' => $user['cluber'],
                    'organizer' => $user['organizer'],
                    'admin' => $user['admin'],
                    'id' => $user['id'],
                    );
            $update='update users set
            first_name=?,
            last_name=?,
            father_name=?,
            email=?,
            password=?,
            phone=?,
            dancer=?,
            trainer=?,
            cluber=?,
            organizer=?,
            admin=?
            where id=?
            ';
            return $this->db->query($update,$data);*/
        $this->db->where('id', $user['id']);
        return $this->db->update('users', $user);
    }

    public function filterUsers($filter)
    {
            $one=true;
            $select='select * from users where';
            if ($filter['filter_admin']>-1){
                    $select.=' admin='.$filter['filter_admin'];
                    $one=false;
            }
            if ($filter['filter_organizer']>-1){
                    $select.=($one==false) ? ' and':'';
                    $select.=' organizer='.$filter['filter_organizer'];
                    $one=false;
            }
            if ($filter['filter_cluber']>-1){
                    $select.=($one==false) ? ' and':'';
                    $select.=' cluber='.$filter['filter_cluber'];
                    $one=false;
            }
            if ($filter['filter_trainer']>-1){
                    $select.=($one==false) ? ' and':'';
                    $select.=' trainer='.$filter['filter_trainer'];
                    $one=false;
            }
            if ($filter['filter_dancer']>-1){
                    $select.=($one==false) ? ' and':'';
                    $select.=' dancer='.$filter['filter_dancer'];
                    $one=false;
            }

            if (strlen($filter['filter_text'])>0){
                    $txt=$filter['filter_text'];
                    $select.=($one==false) ? ' and':'';
                    $select.=" ( first_name LIKE '%".$txt."%'";
                    $select.=" or last_name LIKE '%".$txt."%'";
                    $select.=" or father_name LIKE '%".$txt."%'";
                    $select.=" or phone LIKE '%".$txt."%'";
                    $select.=" or email LIKE '%".$txt."%')";
                    $one=false;
            }
            $query=$this->db->query($select);
            $users=$query->result_array();
            return $users;
    }

    public function delRole($role)
    {
        $id=$this->session->id;
        $update='update users set '.$role.'=0 where id='.$id;
        $this->db->query($update);
        $id=$this->session->set_userdata($role,0);
        return 'deleted';
    }

    public function addRole($role)
    {
        $id=$this->session->id;
        $update='update users set '.$role.'=1 where id='.$id;
        $this->db->query($update);
        $id=$this->session->set_userdata($role,1);
        return 'added';
    }

    public function getRow($table, $id)
    {
        $q = $this->db->query('select * from '.$table.' where id='.$id);
        $res = $q->result_array();
        return $res[0];
    }

    public function update($table,$id,$data)
    {
        $this->db->where('id', $id);
        return $this->db->update($table, $data);
    }

    public function insert($table,$data)
    {
        return $this->db->insert($table, $data);
    }

    public function delete($table,$id,$soft=1)
    {
        $this->db->where('id', $id);
        if ($soft == 1) {
            return $this->db->update($table, array('deleted'=>1));
        } else {
            return $this->db->delete($table);
        }
    }

    public function getStyles($way)
    {
        $q=$this->db->query('select * from styles where deleted=0 and way_id='.$way);
        return $q->result_array();
    }

    public function htmlStyles($way)
    {
        //return $way;
        $data=$this->getStyles($way);
        $html="";
        foreach ($data as $d) {
            $html.='<tr>';
            $html.='<td class="hidden">'.$d['id'].'</td>';
            $html.='<td>'.$d['style'].'</td>';
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

    public function selectStyles($way_id){
        $data=$this->getStyles($way_id);
        $html='<option value="0">выберите стиль</option>';
        foreach ($data as $d) {
            $html.='<option value="'.$d['id'].'">'.$d['style'].'</option>';
        }
        return $html;
    }

    public function getLigs($way)
    {
        $q=$this->db->query('select * from ligs where deleted=0 and way_id='.$way);
        return $q->result_array();
    }

    public function htmlLigs($way)
    {
        $data=$this->getLigs($way);
        $html="";
        foreach ($data as $d) {
            $html.='<tr>';
            $html.='<td class="hidden">'.$d['id'].'</td>';
            $html.='<td>'.$d['number'].'</td>';
            $html.='<td>'.$d['name'].'</td>';
            $html.='<td>'.$d['points'].'</td>';
            if ($d['days'] > 0){
                $html.='<td>'.$d['days'].'</td>';
            }
            else {
                $html.='<td>нет</td>';
            }
            $html.='<td><button class="btn btn-warning btn-sm edit" id="e'.$d['id']
                    .'" data-toggle="modal" data-target="#editmodal">edit</button> ';
            $html.='<button class="btn btn-danger btn-sm del" id="d'.$d['id'].'">delete</button></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function htmlAgeLig($way)
    {
		$q=$this->db->query('select s.id, a.name as age, a.min_age, a.max_age, l.name as lig
		from ligs l, cat_age a, show_ligs s
		where s.lig_id=l.id and s.age_id=a.id and l.way_id='.$way.'
		order by s.age_id');
        $data = $q->result_array();
        $html="";
        foreach ($data as $d) {
            $html.='<tr>';
            $html.='<td class="hidden">'.$d['id'].'</td>';
            $html.='<td>'.$d['age'].' ('.$d['min_age'].'-'.$d['max_age'].' лет)</td>';
            $html.='<td>'.$d['lig'].'</td>';
            $html.='<td><button class="btn btn-danger btn-sm del" id="d'.$d['id'].'">delete</button></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function selectLigs($way)
    {
        $data=$this->getLigs($way);
        $html="";
        foreach ($data as $d) {
            $html.='<option value='.$d['id'].'>'.$d['name'].'</option>';
        }
        return $html;
    }

    public function getDancer($id) {
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.password,'
                . ' d.birthdate, u.dancer, u.phone, d.id, d.user_id, b.name as bell, d.bell_id'
                . ' from users u, dancers d, bellydance b'
                . ' where d.user_id=u.id and d.bell_id=b.id and d.id='.$id);
        $res=$q->result_array();
        $ytime = time() - strtotime($res[0]['birthdate']);
        $res[0]['year'] = ($ytime - $ytime % 31556926) / 31556926;
        $res[0]['exp']=$this->danExpHtml('dancer', $id);
        return $res;
    }

    public function updateDancer($data)
    {
        $dancer=array();
        if (isset($data['birthdate'])){
            $dancer['birthdate'] = $data['birthdate'];
        }
        if (isset($data['bell_id'])){
            $dancer['bell_id'] = $data['bell_id'];
        }
        if (count($dancer)>0){
            $this->db->where('id', $data['id']);
            $this->db->update('dancers', $dancer);
        }
        $user=array(
            'last_name'=>$data['last_name'],
            'first_name'=>$data['first_name'],
            'father_name'=>$data['father_name'],
            'password'=>$data['password'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
        );
        if (isset($data['dancer'])){
            $user['dancer'] = $data['dancer'];
        }
        if (isset($data['user_id'])){
            $user_id=$data['user_id'];
        }else{
            $q=$this->db->query('select user_id from dancers where id='.$data['id']);
            $res=$q->result_array();
            $user_id=$res[0]['user_id'];
        }
        $this->db->where('id', $user_id);
        $this->db->update('users', $user);
        return true;
    }

    public function deactivateDancer($id)
    {
        $this->db->query('update users'
                . ' set dancer=3'
                . ' where id=(select user_id from dancers where id='.$id.')');
        return true;
    }

    public function activateDancer($id)
    {
        $this->db->query('update users'
                . ' set dancer=2'
                . ' where id=(select user_id from dancers where id='.$id.')');
        return true;
    }

    public function getTrainer($id) {
        $q=$this->db->query('select u.first_name, u.last_name, u.father_name, u.email, u.password,'
                . ' u.trainer, u.phone, t.id, t.user_id'
                . ' from users u, trainers t'
                . ' where t.user_id=u.id and t.id='.$id);
        $res=$q->result_array();
        return $res;
    }

    public function getTrainerId($user_id) {
        $q = $this->db->query('select id from trainers where user_id='.$user_id);
        if ($res = $q->result_array()) {
            return $res[0]['id'];
        }
        else {
            return false;
        }
    }

    public function getClubId($user_id) {
        $q = $this->db->query('select id from clubers where user_id='.$user_id);
        if ($res = $q->result_array()) {
            return $res[0]['id'];
        }
        else {
            return false;
        }
    }

    public function deactivateTrainer($id)
    {
        $this->db->query('update users'
                . ' set trainer=3'
                . ' where id=(select user_id from trainers where id='.$id.')');
        return true;
    }

    public function activateTrainer($id)
    {
        $this->db->query('update users'
                . ' set trainer=2'
                . ' where id=(select user_id from trainers where id='.$id.')');
        return true;
    }

    public function updateTrainer($data)
    {
        $user=array(
            'last_name'=>$data['last_name'],
            'first_name'=>$data['first_name'],
            'father_name'=>$data['father_name'],
            'password'=>$data['password'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
        );
        if (isset($data['trainer'])){
            $user['trainer'] = $data['trainer'];
        }
        if (isset($data['user_id'])){
            $user_id=$data['user_id'];
        }else{
            $q=$this->db->query('select user_id from dancers where id='.$data['id']);
            $res=$q->result_array();
            $user_id=$res[0]['user_id'];
        }
        $this->db->where('id', $user_id);
        $this->db->update('users', $user);
    }

    public function selectOrg()
    {
        $q = $this->db->query('select u.last_name, u.first_name, u.father_name, o.id'
                . ' from users u, organizers o'
                . ' where o.user_id=u.id');
        $res = $q->result_array();
        $select='<option value=0>Выберите организатора</option>';
        foreach ($res as $r){
            $select .= '<option value="'.$r['id'].'">';
            $select .= $r['last_name'].' '.$r['first_name'].' '.$r['father_name'];
            $select .= '</option>';
        }
        return $select;
    }

    public function statusId($status_name){
        $q = $this->db->query('select id from statuses where status="'.$status_name.'"');
        $res = $q->result();
        return $res[0]->id;
    }

    public function addCompetition($data)
    {
        $data['status_id']= $this->statusId("ON");
        return $this->db->insert('competitions', $data);
    }

    public function compInfo($id) {
        $q=$this->db->query('select co.name, co.comment, co.city_id, co.way_id, '
                . ' co.date_reg_open, co.date_reg_close, co.date_open, co.date_close, co.status_id, co.org_id,'
                . ' ci.city, ci.region_id, w.way, s.status, u.first_name, u.last_name, u.father_name, u.phone, u.email'
                . ' from competitions co, cities ci, ways w, statuses s, users u, organizers o'
                . ' where co.city_id=ci.id and co.status_id=s.id and co.way_id=w.id and co.org_id=o.id and o.user_id=u.id and co.id='.$id);
        $res=$q->result_array();
        return $res[0];
    }

    public function updateCompetition($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('competitions', $data);
    }

    public function addDancer($data, $trainer_id)
    {
        /*$q = $this->db->query('select id from users where email="'.$data['email'].'"');
        $r = $q->result_array();
        if (count($r)>0) return false;*/
        if (trim($data['birthdate'])=='0000-00-00'){
            return false;
        }
        $user = array(
            'first_name'=>trim($data['first_name']),
            'last_name'=>trim($data['last_name']),
            'father_name'=>trim($data['father_name']),
            'phone'=>trim($data['phone']),
            'email'=>trim($data['email']),
            'password'=>trim($data['password']),
            'dancer'=>2,
            );
         $this->db->insert('users', $user);
        $user_id = $this->db->insert_id();
        $dancer = array(
            'user_id'=>$user_id,
            'birthdate'=>trim($data['birthdate']),
            'bell_id'=>$data['bell_id'],
            'trainer_id'=>$trainer_id,
        );
        $this->db->insert('dancers', $dancer);
        return "OK";
    }

    public function saveExp($data)
    {
        $q=$this->db->query('select id from experience where dancer_id='.$data['dancer_id'].' and way_id='.$data['way_id']);
        $test = $q->result_array();
        if (count($test)==0){
            $data['create_at'] = date('Y-m-d',time());
            return $this->db->insert('experience', $data);
        } else {
            return false;
        }

    }

    public function addSummCats($data)
    {
        $dancers=$data['dancers'];
        $cats_all=$data['cats'];
        $comp_id=$data['competition'][0]['value'];
        /*проверка на повторение*/
        $q = $this->db->query('select dancer_id, lig_id, style_id, age_id, count_id, comp_id'
                . ' from comp_list where comp_id='.$comp_id);
        $test = $q->result_array();
        $cats=[];
        foreach ($cats_all as $cat){
            $find = false;
            foreach ($dancers as $dan){
                foreach ($test as $t){
                    if ($dan['value'] == $t['dancer_id']
                        && $cat['lig_id'] == $t['lig_id']
                        && $cat['style_id'] == $t['style_id']
                        && $cat['age_id'] == $t['age_id']
                        && $cat['count_id'] == $t['count_id']
                            ){
                        $find = true;
                    }
                }
            }
            if ($find == false){
                $cats[] = $cat;
            }
        }
        //$cats = $cats_all;
        /*добавление*/
        foreach ($cats as $cat){
            $q = $this->db->query('select MAX(part) as max_part from comp_list');
            $res = $q->result();
            $prev_part = $res[0]->max_part;
            if (is_null($prev_part)){
                $next_part = 1;
            }else{
                $next_part = $prev_part + 1;
            }
            foreach ($dancers as $dancer){
                $ins=array(
                    'dancer_id'=>$dancer['value'],
                    'lig_id'=>$cat['lig_id'],
                    'style_id'=>$cat['style_id'],
                    'age_id'=>$cat['age_id'],
                    'count_id'=>$cat['count_id'],
                    'comp_id'=>$comp_id,
                    'part'=>$next_part
                        );
                $this->db->insert('comp_list',$ins);
            }
            return $find;
        }
        /*
        $q = $this->db->query('select count_id, lig_id, comp_id'
                . ' from pays'
                . ' where comp_id='.$comp_id);
        $pay_list = $q->result_array();
        $insert=array();
        foreach ($cats as $c){
            $find = FALSE;
            foreach ($pay_list as $p){
                if ($c['count_id'] == $p['count_id'] && $c['lig_id'] == $p['lig_id']  && $c['comp_id'] == $p['comp_id']){
                    $find = TRUE;
                }
            }
            if ($find == FALSE){
                $insert[]=[
                    'count_id'=>$c['count_id'],
                    'lig_id'=>$c['lig_id'],
                    'comp_id'=>$comp_id
                        ];
            }
        }
        if (count($insert) > 0) $q= $this->db->insert_batch('pays',$insert);*/
        return true;
    }

    public function AdminCompList($comp_id, $role)
    {
        $rows = $this->getCompList($comp_id, $role);
        $html='';
        foreach ($rows as $row){
            $html.='<tr>';
            $html.='<td>'.$row['print_number'].'</td>';
            $html.='<td>'.$row['last_name'].' '.$row['first_name'].'</td>';
            $html.='<td>'.substr($row['birthdate'],0,4).'</td>';
            if ($row['danlig']==null){
                $row['danlig']='Дебют';
            }
            $html.='<td>'.$row['danlig'].'</td>';
            $html.='<td>'.$row['points'].'</td>';
            $html.='<td>'.$row['style'].'</td>';
            $html.='<td>'.$row['age_cat'].'</td>';
            $html.='<td>'.$row['count_cat'].'</td>';
            $html.='<td>'.$row['lig'].'</td>';
            $html.='<td>'.$row['bell'].'</td>';
            if ($row['type']==1) $html.='<td>'.$row['pay_iude'].'</td>';
            if ($row['type']==2) $html.='<td>'.$row['pay_other'].'</td>';
            if ($row['type']==3) $html.='<td>'.$row['pay_not'].'</td>';
            $html.='<td><a href="'.$row['part'].'" class="deldan">'
                    . '<img src="/img/delete_16.png" alt="del"></a></td>';
            $html.='</tr>';
        }
        return $html;
    }


    public function getCompListHtml($comp_id, $role, $role_id = 0)
    {
        $rows = $this->getCompList($comp_id, $role, $role_id);
        $html='';
        foreach ($rows as $row){
            $html.='<tr>';
            $html.='<td>'.$row['last_name'].' '.$row['first_name'].'</td>';
            $html.='<td>'.$row['style'].' '.$row['age_cat'].' '.$row['count_cat'].' '.$row['lig'].'</td>';
            if ($row['type']==1) $html.='<td>'.$row['pay_iude'].'</td>';
            if ($row['type']==2) $html.='<td>'.$row['pay_other'].'</td>';
            if ($row['type']==3) $html.='<td>'.$row['pay_not'].'</td>';
            $html.='<td>'.$row['print_number'].'</td>';
            $html.='<td><a href="'.$row['part'].'" class="deldan">'
                    . '<img src="/img/delete_16.png" alt="del"></a></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function getCompList($comp_id, $role, $role_id=0)
    {
        switch ($role){
            case 'trainer':
                $q = $this->db->query('select DISTINCT d.id, u.first_name, u.last_name, cl.part,'
                        . ' b.type, cl.print_number,'
                        . ' (select pays.pay_iude from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_iude,'
                        . ' (select pays.pay_not from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_not,'
                        . ' (select pays.pay_other from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_other,'
                        . ' l.name as lig, s.style, cc.name as count_cat, ca.name as age_cat,'
                        . ' (select last_name from users where users.id=t.user_id) as trainer_name,'
                        . ' co.name as comp_name, clubers.title'
                        . ' from ligs l, styles s, cat_count cc, cat_age ca, users u, dancers d,'
                        . ' comp_list cl, bellydance b, trainers t, competitions co, clubers'
                        . ' where cl.dancer_id=d.id and cl.lig_id=l.id and cl.style_id=s.id'
                        . ' and cl.age_id=ca.id and cl.count_id=cc.id and d.user_id=u.id'
                        . ' and cl.comp_id='.$comp_id.' and d.trainer_id='.$role_id.''
                        . ' and d.bell_id=b.id and co.id=cl.comp_id'
                        . ' and d.trainer_id=t.id and t.club_id=clubers.id'
                        . ' order by cl.part asc');
                $res = $q->result_array();
                break;
            case 'admin':
                $q = $this->db->query('select DISTINCT d.id, d.birthdate, u.first_name, u.last_name, u.first_name, u.father_name, cl.part, cl.points, cl.id as cl_id,'
                        . ' (select name from ligs where id=(select experience.lig_id from experience where experience.dancer_id=d.id '
                        . ' and experience.way_id=(select way_id from competitions where id='.$comp_id.'))) as danlig,'
                        . ' b.type, d.birthdate,b.name as bell, clubers.title,'
                        . ' (select pays.pay_iude from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_iude,'
                        . ' (select pays.pay_not from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_not,'
                        . ' (select pays.pay_other from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_other,'
                        . ' (select last_name from users where users.id=t.user_id) as trainer_name,'
                        . ' (select first_name from users where users.id=t.user_id) as trainer_first_name,'
                        . ' (select last_name from users where users.id=clubers.user_id) as clubers_name,'
                        . ' (select first_name from users where users.id=clubers.user_id) as clubers_first_name,'
                        . ' (select count(comp.id) from comp_list as comp where comp.part=cl.part) as part_count,'
                        . ' (select region from regions where ci.region_id=regions.id) as region,'
                        . ' co.name as comp_name, ci.city,'
                        . ' l.name as lig, s.style, cc.name as count_cat, ca.name as age_cat, cl.print_number'
                        . ' from ligs l, styles s, cat_count cc, cat_age ca, users u, dancers d,'
                        . ' comp_list cl, bellydance b, clubers, trainers t, competitions co'
                        . ', cities ci'
                        . ' where cl.dancer_id=d.id and cl.lig_id=l.id and cl.style_id=s.id'
                        . ' and cl.age_id=ca.id and cl.count_id=cc.id and d.user_id=u.id'
                        . ' and clubers.city_id=ci.id'
                        . ' and d.trainer_id=t.id and t.club_id=clubers.id'
                        . ' and co.id=cl.comp_id'
                        . ' and d.bell_id=b.id and cl.comp_id='.$comp_id.' '
                        . ' order by cl.part asc');
                $res = $q->result_array();
                break;
            case 'cluber':
                $q = $this->db->query('select DISTINCT d.id, u.first_name, u.last_name,cl.part,'
                        . ' b.type, cl.print_number,'
                        . ' (select pays.pay_iude from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_iude,'
                        . ' (select pays.pay_not from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_not,'
                        . ' (select pays.pay_other from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_other,'
                        . ' l.name as lig, s.style, cc.name as count_cat, ca.name as age_cat,'
                        . ' (select last_name from users where users.id=t.user_id) as trainer_name,'
                        . ' co.name as comp_name, clubers.title'
                        . ' from ligs l, styles s, cat_count cc, cat_age ca, users u, dancers d,'
                        . ' comp_list cl, bellydance b, trainers t, competitions co, clubers'
                        . ' where cl.dancer_id=d.id and cl.lig_id=l.id and cl.style_id=s.id'
                        . ' and cl.age_id=ca.id and cl.count_id=cc.id and d.user_id=u.id'
                        . ' and cl.comp_id='.$comp_id.' and t.club_id='.$role_id.''
                        . ' and d.bell_id=b.id and d.trainer_id=t.id and co.id=cl.comp_id'
                        . ' and d.trainer_id=t.id and t.club_id=clubers.id'
                        . ' order by cl.part asc');
                $res = $q->result_array();
                break;
            case 'organizer':
                $q = $this->db->query('select DISTINCT d.id, u.first_name, u.last_name, d.birthdate,cl.part,'
                        . ' b.type, cl.print_number,'
                        . ' (select pays.pay_iude from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_iude,'
                        . ' (select pays.pay_not from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_not,'
                        . ' (select pays.pay_other from pays where pays.comp_id=cl.comp_id and pays.lig_id=cl.lig_id and pays.count_id=cl.count_id) as pay_other,'
                        . ' l.name as lig, s.style, cc.name as count_cat, ca.name as age_cat,'
                        . ' (select last_name from users where users.id=t.user_id) as trainer_name,'
                        . ' co.name as comp_name, clubers.title, ci.city'
                        . ' from cities ci, ligs l, styles s, cat_count cc, cat_age ca, users u, dancers d,'
                        . ' comp_list cl, bellydance b, competitions co, clubers, trainers t'
                        . ' where cl.dancer_id=d.id and cl.lig_id=l.id and cl.style_id=s.id'
                        . ' and cl.age_id=ca.id and cl.count_id=cc.id and d.user_id=u.id'
                        . ' and d.bell_id=b.id and cl.comp_id='.$comp_id.' '
                        . ' and clubers.city_id=ci.id'
                        . ' and d.trainer_id=t.id and t.club_id=clubers.id'
                        . ' and co.id=cl.comp_id'
                        . ' order by cl.part asc');
                $res = $q->result_array();
                break;
        }
        return $res;
    }

    public function getAdminCompList($comp_id)
    {
        $res = $this->getCompList($comp_id, 'admin');
        $arr = [];
        $i = 0;
        foreach ($res as $r) {
            $arr[$i] = [];
            $arr[$i]['id'] = $r['id'];
            $arr[$i]['№ участия (печатаемый)'] = $r['print_number'];
            $arr[$i]['ФИ танцоров'] = $r['last_name'] . ' ' . $r['first_name'];
            $arr[$i]['Город'] = $r['city'];
            $arr[$i]['Клуб'] = $r['title'];
            $arr[$i]['Руководитель'] = $r['clubers_name'] . ' ' . $r['clubers_first_name'];
            $arr[$i]['Тренер'] = $r['trainer_name'] . ' ' . $r['trainer_first_name'];
            $arr[$i]['Год рождения'] = substr($r['birthdate'], 0, 4);
            $arr[$i]['Область'] = $r['region'];
            $arr[$i]['Стиль'] = $r['style'];
            $arr[$i]['Возраст кат.'] = $r['age_cat'];
            $arr[$i]['Коллич. кат.'] = $r['count_cat'];
            $arr[$i]['Лига'] = $r['lig'];
            $arr[$i]['Кол-во уч-в в группе'] = $r['part_count'];
            $arr[$i]['Организация'] = $r['bell'];
            if ($r['type'] == 1) {
                $arr[$i]['Взнос грн.'] = $r['pay_iude'];
            } elseif ($r['type'] == 2) {
                $arr[$i]['Взнос грн.'] = $r['pay_other'];
            } else {
                $arr[$i]['Взнос грн.'] = $r['pay_not'];
            }
            $i++;
        }
        return $arr;
    }

    public function getCompList2($comp_id, $role, $role_id=0)
    {
        $res = $this->getCompList($comp_id, $role, $role_id);
        $arr = [];
        $i = 0;
        foreach($res as $r){
            $arr[$i]=[];
            foreach($r as $k=>$v){
                switch ($k){
                    case 'id':
                        $arr[$i]['id'] = $v;
                        break;
                    case 'first_name':
                        $arr[$i]['имя'] = $v;
                        break;
                    case 'last_name':
                        $arr[$i]['фамилия'] = $v;
                        break;
                    case 'print_number':
                        $arr[$i]['номер печать'] = $v;
                        break;
                    case 'points':
                        $arr[$i]['очки'] = $v;
                        break;
                    case 'danlig':
                        $arr[$i]['лига танцора'] = $v;
                        break;
                    case 'birthdate':
                        $arr[$i]['дата рождения'] = $v;
                        break;
                    case 'bell':
                        $arr[$i]['членство'] = $v;
                        break;
                    case 'lig':
                        $arr[$i]['лига соревн'] = $v;
                        break;
                    case 'style':
                        $arr[$i]['стиль танца'] = $v;
                        break;
                    case 'count_cat':
                        $arr[$i]['кат.по кол.'] = $v;
                        break;
                    case 'age_cat':
                        $arr[$i]['кат.по возр'] = $v;
                        break;
                    case 'pay_iude':
                        if ($r['type']==1){
                            $arr[$i]['взнос'] = $v;
                        }
                        break;
                    case 'pay_other':
                        if ($r['type']==2){
                            $arr[$i]['взнос'] = $v;
                        }
                        break;
                    case 'pay_not':
                        if ($r['type']==3){
                            $arr[$i]['взнос'] = $v;
                        }
                        break;
                    case 'title':
                        $arr[$i]['клуб'] = $v;
                        break;
                    case 'city':
                        $arr[$i]['город'] = $v;
                        break;
                    case 'trainer_name':
                        $arr[$i]['тренер'] = $v;
                        break;
                    case 'comp_name':
                        $arr[$i]['конкурс'] = $v;
                        break;
                    case 'type':
                        break;
                    default :
                    break;
                }
            }
            $i++;
        }
        return $arr;
    }

    public function getCompListCsv($comp_id, $role, $role_id)
    {
        $rows = $this->getCompList($comp_id, $role, $role_id);
        $html='';
        foreach ($rows as $row){
            $html.=$row['last_name'].' '.$row['first_name'].';';
            $html.=$row['style'].' '.$row['age_cat'].' '.$row['count_cat'].' '.$row['lig'];
            if ($row['type']==1) $html.=';'.$row['pay_iude'];
            if ($row['type']==2) $html.=';'.$row['pay_other'];
            if ($row['type']==3) $html.=';'.$row['pay_not'];
            $html.="\r\n";
        }
        force_download('list.csv',$html);
        return $name;
    }

    public function getResultCsv($comp_id, $role, $role_id = 0)
    {
        $rows = $this->getCompResult($comp_id, $role, $role_id);
        $html='';
        foreach ($rows as $row){
            $html.=$row['last_name'].' '.$row['first_name'].';';
            $html.=$row['city'].';';
            $html.=$row['title'].';';
            $html.=$row['tr_last_name'].' '.$row['tr_first_name'].';';
            $html.=substr($row['birthdate'],0,4).';';
            $ytime = time() - strtotime($row['birthdate']);
            $year = ($ytime - $ytime % 31556926) / 31556926;
            $html.=$year.';';
            $html.=$row['style'].';';
            $html.=$row['count'].';';
            $html.=$row['lig'].';';
            $html.=$row['place'];
            $html.="\r\n";
        }
        $file='csv/list'.$this->session->id.'.csv';
        $h=fopen($file,"w");
        fwrite($h, $html);
        fclose($h);
        return $file;
    }

    public function getCompResult($comp_id, $role, $role_id)
    {
        switch ($role){
            case 'trainer':
                break;
            case 'admin':
                $q = $this->db->query('select comp_list.id, users.last_name, users.first_name, cities.city, clubers.title,'
                        . ' (select users.last_name from users, trainers where trainers.user_id=users.id and dancers.trainer_id=trainers.id) as tr_last_name,'
                        . ' (select users.first_name from users, trainers where trainers.user_id=users.id and dancers.trainer_id=trainers.id) as tr_first_name,'
                        . ' dancers.birthdate, styles.style, cat_count.name as count, ligs.name as lig, comp_list.place'
                        . ' from dancers, users, trainers, comp_list, cities, clubers, styles,'
                        . ' cat_count, ligs'
                        . ' where comp_list.dancer_id=dancers.id and dancers.user_id=users.id'
                        . ' and dancers.trainer_id=trainers.id and trainers.club_id=clubers.id'
                        . ' and clubers.city_id=cities.id and comp_list.count_id=cat_count.id'
                        . ' and comp_list.lig_id=ligs.id and comp_list.style_id=styles.id and comp_id='.$comp_id);
                $res = $q->result_array();
                break;
        }
        return $res;
    }

    public function getResultHtml($comp_id, $role, $role_id = 0)
    {
        $rows = $this->getCompResult($comp_id, $role, $role_id);
        $html='';
        foreach ($rows as $row){
            $html.='<tr>';
            $html.='<td>'.$row['last_name'].' '.$row['first_name'].'</td>';
            $html.='<td>'.$row['city'].'</td>';
            $html.='<td>'.$row['title'].'</td>';
            $html.='<td>'.$row['tr_last_name'].' '.$row['tr_first_name'].'</td>';
            $html.='<td>'.substr($row['birthdate'],0,4).'</td>';
            $ytime =time() - strtotime($row['birthdate']);
            $year = ($ytime - $ytime % 31556926) / 31556926;
            $html.='<td>'.$year.'</td>';
            $html.='<td>'.$row['style'].'</td>';
            $html.='<td>'.$row['count'].'</td>';
            $html.='<td>'.$row['lig'].'</td>';
            $html.='<td>'.$row['place'].'</td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function savePays($data)
    {
        for ($i=0;$i<count($data['id']);$i++){
            $ins=[
                'pay_iude'=>$data['pay_iude'][$i],
                'pay_other'=>$data['pay_other'][$i],
                'pay_not'=>$data['pay_not'][$i]
            ];
            $this->db->where('id',$data['id'][$i]);
            $this->db->update('pays',$ins);
        }
        return true;
    }

    public function getCompReward($comp_id)
    {
        $sum_medal1=0;
        $sum_medal2=0;
        $sum_medal3=0;
        $sum_kub1=0;
        $sum_kub2=0;
        $sum_kub3=0;
        $q = $this->db->query('select count(cl.id) as solo'
                . ' from comp_list cl, ligs l, cat_count cc'
                . ' where cl.lig_id=l.id and cl.count_id=cc.id'
                . ' and cc.name="Соло" and l.name="Дебют" and cl.comp_id='.$comp_id);
        $res = $q->result_array();
        if (count($res)>0) $html='<tr><td>Дебют Соло</td><td>'.$res[0]['solo'].'</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>';
        else $html='<tr><td>Дебют Соло</td><td> - </td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>';

        $q = $this->db->query('select DISTINCT l.name as lig_name, cc.name as count_name,'
                . ' cl.lig_id, cl.style_id, cl.age_id, cl.count_id, cl.part'
                . ' from comp_list cl, ligs l, cat_count cc'
                . ' where cl.lig_id=l.id and cl.count_id=cc.id and cl.comp_id='.$comp_id.' '
                . '  order by cl.style_id, cl.age_id ASC');
        $data= $q->result_array();
        if (count($data)==0) return $html;
        $q = $this->db->query('select DISTINCT cc.max_count, l.name as lig_name, cc.name as count_name,'
                . ' cl.lig_id, cl.count_id'
                . ' from comp_list cl, cat_count cc, ligs l'
                . ' where cl.lig_id=l.id and cl.count_id=cc.id'
                . ' and not (l.name="Дебют" and cc.name="Соло")'
                . ' and cl.comp_id='.$comp_id);
        $cats= $q->result_array();

        $data_sum = [];
        $data_sum[0] = [];
        $data_sum[0]['lig_id'] = $data[0]['lig_id'];
        $data_sum[0]['style_id'] = $data[0]['style_id'];
        $data_sum[0]['age_id'] = $data[0]['age_id'];
        $data_sum[0]['count_id'] = $data[0]['count_id'];
        $data_sum[0]['count']=0;

        foreach($data as $d){
            $new = TRUE;
            foreach ($data_sum as $k => $ds){
                if ($ds['lig_id'] == $d['lig_id'] && $ds['style_id'] == $d['style_id']
                    && $ds['count_id'] == $d['count_id'] && $ds['age_id'] == $d['age_id']){
                    $new=FALSE;
                    $data_sum[$k]['count']++;
                }
            }
            if ($new){
                $index=count($data_sum);
                $data_sum[$index]['lig_id'] = $d['lig_id'];
                $data_sum[$index]['style_id'] = $d['style_id'];
                $data_sum[$index]['age_id'] = $d['age_id'];
                $data_sum[$index]['count_id'] = $d['count_id'];
                $data_sum[$index]['count']=1;
            }
        }

        $sum_medal1=0;
        $sum_medal2=0;
        $sum_medal3=0;
        $sum_kub1=0;
        $sum_kub2=0;
        $sum_kub3=0;
        foreach ($cats as $cat){
            $medal1=0;
            $medal2=0;
            $medal3=0;
            $kub1=0;
            $kub2=0;
            $kub3=0;
            $count=0;
            foreach ($data_sum as $d){
                if ($cat['lig_id'] == $d['lig_id'] && $cat['count_id'] == $d['count_id']){
                    if ($cat['max_count']<4){
                        $medal1 += 1 * $cat['max_count'];
                        if ($d['count']>1){
                            $medal2 += 1 * $cat['max_count'];
                        }
                        if ($d['count']>2){
                            $medal3 += 1 * $cat['max_count'];
                        }
                    }else{
                        $kub1++;
                        if ($d['count']>1){
                            $kub2++;
                        }
                        if ($d['count']>2){
                            $kub3++;
                        }
                    }
                }
            }
            $sum_medal1+=$medal1;
            $sum_medal2+=$medal2;
            $sum_medal3+=$medal3;
            $sum_kub1+=$kub1;
            $sum_kub2+=$kub2;
            $sum_kub3+=$kub3;
            $html.='<tr><td>'.$cat['count_name'].' '.$cat['lig_name'].'</td><td>-</td>'
                    .'<td>'.$medal1.'</td><td>'.$medal2.'</td><td>'.$medal3.'</td>'
                    . '<td>'.$kub1.'</td><td>'.$kub2.'</td><td>'.$kub3.'</td></tr>';
        }
        $html.='<tr class="success"><td>ИТОГО</td><td>-</td>'
                    .'<td>'.$sum_medal1.'</td><td>'.$sum_medal2.'</td><td>'.$sum_medal3.'</td>'
                    . '<td>'.$sum_kub1.'</td><td>'.$sum_kub2.'</td><td>'.$sum_kub3.'</td></tr>';
        return $html;
    }

    public function delsl($str){
        $res = '';
        for($i=0; $i<strlen($str); $i++){
            if (substr($str,$i,1)!='"'){
                $res.= substr($str,$i,1);
            }
        }
        return $res;
    }

    public function uploadResult($file, $comp_id)
    {
        $data=[];
        $handle = fopen("csv/".$file, "r");
        $first = true;
        while($str = fgets($handle)){
            if ($first == true){
                $first = false;
            } else{
                $res = explode(";",iconv('Windows-1251','UTF-8',$str));
                $b = trim($res[5]);
                $b = substr($b, 6, 4).'-'.substr($b, 3, 2).'-'.substr($b, 0, 2);
                $c = $this->delsl(trim($res[7]));
                $data[] = [
                    'first_name'=>trim($res[0]),
                    'last_name'=>trim($res[1]),
                    'birthdate'=> $b,
                    'club'=>$c,
                    'trainer'=>trim($res[8]),
                    'lig'=>trim($res[11]),
                    'style'=>trim($res[12]),
                    'count_name'=>trim($res[13]),
                    'place'=>trim($res[16]),
                ];
            }

        }
        fclose($handle);
        unlink("csv/".$file);
        $comp_list = $this->getCompResult($comp_id, 'admin', 0);
        /*
        users.last_name,
        users.first_name,
        clubers.title,
        tr_last_name,
        dancers.birthdate,
        styles.style,
        cat_count.name as count,
        ligs.name as lig,
        */
        $lost_arr=[];
        $temp='f=';
        foreach ($data as $d){
            if ($d['first_name']=='ИТОГО:'){
                break;
            }
            $lost=true;
            foreach ($comp_list as $c){
                /*
                 if ($d['last_name']==$c['last_name'] && $d['first_name']==$c['first_name']
                        && $d['club']==$c['title'] && $d['trainer']==$c['tr_last_name']
                        && $d['birthdate']==$c['birthdate'] && $d['style']==$c['style']
                        && $d['count_name']==$c['count'] && $c['lig']==$d['lig']){
                 */
                if ($d['last_name']==trim($c['last_name']) && $d['first_name']==trim($c['first_name'])
                        && $d['club']==trim($this->delsl($c['title']))
                        && $d['birthdate']==trim($c['birthdate']) && $d['style']==trim($c['style'])
                        && $d['count_name']==trim($c['count']) && $c['lig']==trim($d['lig'])){
                    $lost=false;
                    $this->db->where('id',$c['id']);
                    $this->db->update('comp_list',['place'=>$d['place']]);
                    $temp .= '  ='.$c['id'];
                }
            }
            if ($lost){
                $lost_arr[] = $d;
            }
        }
        //return $temp;
        $html = '';
        foreach ($lost_arr as $l){
            $html.='<tr>';
            $html.='<td>'.$l['last_name'].' '.$l['first_name'].'</td>';
            $html.='<td>'.$l['birthdate'].'</td>';
            $html.='<td>'.$l['club'].'</td>';
            $html.='<td>'.$l['trainer'].'</td>';
            $html.='<td>'.$l['style'].'</td>';
            $html.='<td>'.$l['count_name'].'</td>';
            $html.='<td>'.$l['lig'].'</td>';
            $html.='<td>'.$l['place'].'</td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function doneComp($comp_id)
    {
        $q = $this->db->query('select s.status'
                . ' from competitions c, statuses s'
                . ' where c.status_id=s.id and c.id='.$comp_id);
        $res = $q->result();
        if ($res[0]->status == 'DONE') return 'NO';
        $this->db->query('update competitions'
                . ' set status_id=(select id from statuses where status="DONE")'
                . ' where id='.$comp_id);
        $q = $this->db->query('select id, part, place'
                . ' from comp_list'
                . ' where comp_id='.$comp_id);
        $dancers = $q->result_array();
        $q = $this->db->query('SELECT part, count(part) as num FROM comp_list
                                where comp_id='.$comp_id.'
                                group by part');
        $parts = $q->result_array();
        $q = $this->db->query('SELECT minn, maxn, reight, points FROM reight');
        $reight = $q->result_array();
        $lost = FALSE;
        $mess='OK';
        foreach ($dancers as $d){
            foreach ($parts as $p){
                if ($p['part']==$d['part']){
                    $num=$p['num'];
                }
            }
            $lost_one = TRUE;
            foreach ($reight as $r){
                if ($d['place']==$r['reight'] && $num>=$r['minn'] && $num<=$r['maxn']){
                    $lost_one=FALSE;
                    $points=$r['points']+1;
                    $this->db->where('id',$d['id']);
                    $this->db->update('comp_list',['points'=>$points]);
                }
            }
            if ($lost_one) {
                $lost = TRUE;
            }
        }
        if ($lost){
            $mess='ERROR';
        }
        $q = $this->db->query('select way_id from competitions where id='.$comp_id);
        $res= $q->result();
        $way_id= $res[0]->way_id;
        $q = $this->db->query('select dancer_id, sum(points) as point'
                . ' from comp_list where comp_id='.$comp_id
                . ' group by dancer_id');
        $comp_points = $q->result_array();
        foreach($comp_points as $c) {
            if ($c['point'] == 0) {
                continue;
            }
            $q = $this->db->query('select e.id, e.points, l.points as next_p, l.number, e.create_at, l.days'
                    . ' from experience as e, ligs as l, dancers d '
                    . ' where e.dancer_id='.$c['dancer_id']
                    . ' and e.way_id='.$way_id.' and e.lig_id=l.id and e.dancer_id=d.id');
            $res = $q->result();
            if (count($res) == 0) {
                $time=date('Y-m-d',time());
                $ins=$this->db->query('insert into experience (dancer_id, lig_id, points, way_id, create_at)'
                        . ' values ('
                        . $c['dancer_id']
                        . ',(select id from ligs where way_id='.$way_id.' and name="Дебют")'
                        . ','.$c['point']
                        . ','.$way_id
                        . ',"'.$time
                        . '")');
            } else {
                $prev = strtotime($res[0]->create_at);
                $del = $prev + $res[0]->days * 24 * 60 * 60 - time();
                if ($res[0]->days > 0 && $del < 0){
                    $q= $this->db->query('select id '
                            . ' from ligs'
                            . ' where number=' . ($res[0]->number + 1) . ' and way_id=' . $way_id);
                    $r = $q->result();
                    $lig_id = $r[0]->id;
                    $ins = [
                        'lig_id' => $lig_id,
                        'points' => $res[0]->points
                    ];
                    $this->db->where('id', $res[0]->id);
                    $this->db->update('experience', $ins);
                } else {
                    $sum = $res[0]->points + $c['point'];
                    if ($sum < $res[0]->next_p){
                        $this->db->where('id', $res[0]->id);
                        $this->db->update('experience', ['points' => $sum]);
                    } else {
                        $q = $this->db->query('select id '
                                . ' from ligs'
                                . ' where number=' . ($res[0]->number + 1) . ' and way_id=' . $way_id);
                        $r = $q->result();
                        $lig_id = $r[0]->id;
                        //$sum = $sum - $res[0]->next_p;
                        $sum = 0;
                        $ins = [
                            'lig_id' => $lig_id,
                            'points' => $sum
                        ];
                        $this->db->where('id', $res[0]->id);
                        $this->db->update('experience', $ins);
                    }
                }
            }
        }
        return $mess;
    }

    public function recoverCompetition($comp_id)
    {
        $sel  = 'select id, dancer_id, points'
                . ' from comp_list'
                . ' where comp_id=' . $comp_id;
        $q = $this->db->query($sel);
        $competition = $q->result_array();
        $sel  = 'select ex.id, ex.dancer_id, ex.lig_id, ex.points'
                . ' from experience ex, competitions co'
                . ' where ex.way_id=co.way_id and co.id=' . $comp_id
                . ' and ex.dancer_id in (select DISTINCT dancer_id from comp_list where comp_id=' . $comp_id . ')';
        $q = $this->db->query($sel);
        $exp = $q->result_array();
        $sel = 'select l.id, l.points, l.number'
                . ' from ligs as l, competitions as c'
                . ' where l.deleted=0 and l.way_id = c.way_id and c.id=' . $comp_id
                . ' order by number asc';
        $q = $this->db->query($sel);
        $ligs = $q->result_array();
        $test = [];
        foreach ($exp as $ex) {
            $points = $ex['points'];
            foreach ($competition as $comp) {
                if ($ex['dancer_id'] == $comp['dancer_id']) {
                    $points -= $comp['points'];
                }
            }
            $ex_lig = $ex['lig_id'];
            //while ($points <0 ) {
            if ($points <0) {
                foreach ($ligs as $lig_key => $lig_val) {
                    if ($lig_val['id'] == $ex['lig_id']) {
                        if ($lig_val['number'] > 1) {
                            $ex['lig_id'] = $ligs[$lig_key - 1]['id'];
                            $points += $ligs[$lig_key - 1]['points'];
                        } else {
                            $points = 0;
                        }
                    }
                }
            }
            $upd = 'update experience'
                    . ' set points=' . $points
                    . ', lig_id=' . $ex['lig_id']
                    . ' where id=' . $ex['id'];
            $this->db->query($upd);
        }
        $upd = 'update comp_list'
                . ' set points=0'
                . ' where comp_id=' . $comp_id;
        $this->db->query($upd);
        $upd = 'update competitions'
                . ' set status_id = (select id from statuses where status="CLOSE")'
                . ' where id=' . $comp_id;
        $this->db->query($upd);
        return true;
    }

    public function closeComp($comp_id)
    {
        $res = $this->db->query('update competitions set status_id='
                . '(select id from statuses where status="CLOSE")'
                . ' where id='.$comp_id);
        return $res;
    }

    public function getYearPay($type)
    {
        switch ($type){
            case "all":
                $sel='select u.last_name, u.first_name, u.father_name, d.id,'
                    . ' u.phone, u.email, c.title, d.pay'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id';
                break;
            case "yes":
                $now=date('Y',time());
                $sel='select u.last_name, u.first_name, u.father_name, d.id, '
                    . ' u.phone, u.email, c.title, d.pay'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id'
                    . ' and d.pay>='.$now;
                break;
            case "no":
                $now=date('Y',time());
                $sel='select u.last_name, u.first_name, u.father_name,'
                    . ' u.phone, u.email, c.title, d.pay, d.id'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id'
                    . ' and (ISNULL(d.pay) or d.pay<'.$now.')';
                break;
        }
        $q = $this->db->query($sel);
        $res = $q->result_array();
        $html='';
        foreach ($res as $r){
            if ($r['pay']==null) $r['pay']=0;
            $html.='<tr>';
            $html.='<td><input type="hidden" name="id[]" value='.$r['id'].'>';
            $html.=$r['last_name'].' '.$r['first_name'].' '.$r['father_name'].'</td>';
            $html.='<td>'.$r['email'].' '.$r['phone'].'</td>';
            $html.='<td>'.$r['title'].'</td>';
            $html.='<td><input type="number" name="year[]" value='.$r['pay'].' class="col-xs-5"></td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function getYearPay2($type, $num, $page)
    {
        switch ($type){
            case "all":
                $sel='select u.last_name, u.first_name, u.father_name, d.id,'
                    . ' u.phone, u.email, c.title, d.pay'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id'
                    . ' order by d.id'
                    . ' limit ' . (($page - 1) * $num) . ',' . $num;
                $q = $this->db->query($sel);
                $res = $q->result_array();
                $q = $this->db->query('select count(d.id) as num'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id');
                $r = $q->result();
                $all = $r[0]->num;
                $pages = ceil($all / $num);
                break;
            case "yes":
                $now=date('Y',time());
                $sel='select u.last_name, u.first_name, u.father_name, d.id, '
                    . ' u.phone, u.email, c.title, d.pay'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id'
                    . ' and d.pay>='.$now
                    . ' order by d.id'
                    . ' limit ' . (($page - 1) * $num) . ',' . $num;
                $q = $this->db->query($sel);
                $res = $q->result_array();
                $q = $this->db->query('select count(d.id) as num'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id'
                    . ' and d.pay>='.$now);
                $r = $q->result();
                $all = $r[0]->num;
                $pages = ceil($all / $num);
                break;
            case "no":
                $now=date('Y',time());
                $sel='select u.last_name, u.first_name, u.father_name,'
                    . ' u.phone, u.email, c.title, d.pay, d.id'
                    . ' from users u, dancers d, trainers t, clubers c'
                    . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id'
                    . ' and (ISNULL(d.pay) or d.pay<'.$now.')'
                    . ' order by d.id'
                    . ' limit ' . (($page - 1) * $num) . ',' . $num;
                $q = $this->db->query($sel);
                $res = $q->result_array();
                 $q = $this->db->query('select count(d.id) as num'
                 . ' from users u, dancers d, trainers t, clubers c'
                 . ' where d.user_id=u.id and d.trainer_id=t.id and t.club_id=c.id'
                 . ' and (ISNULL(d.pay) or d.pay<'.$now.')');
                $r = $q->result();
                $all = $r[0]->num;
                $pages = ceil($all / $num);
                break;
        }

        $html='';
        foreach ($res as $r){
            if ($r['pay']==null) $r['pay']=0;
            $html.='<tr>';
            $html.='<td><input type="hidden" name="id[]" value='.$r['id'].'>';
            $html.=$r['last_name'].' '.$r['first_name'].' '.$r['father_name'].'</td>';
            $html.='<td>'.$r['email'].' '.$r['phone'].'</td>';
            $html.='<td>'.$r['title'].'</td>';
            $html.='<td><input type="number" name="year[]" value='.$r['pay'].' class="col-xs-5"></td>';
            $html.='</tr>';
        }
        $data=[];
        $data['list'] = $html;
        $data['pagg'] = '<ul class="pager">';
        if ($page == 1){
            $data['pagg'] .= '<li class="disabled"><a href="#" id="prev">Предыдущая</a></li>';
        } else{
            $data['pagg'] .= '<li><a href="#" id="prev">Предыдущая</a></li>';
        }
        $data['pagg'] .= '<li> СТРАНИЦА '.$page.' из '.$pages.' </li>';
        if ($page == $pages){
            $data['pagg'] .= '<li class="disabled"><a href="#" id="next">Следующая</a></li></ul>';
        } else{
            $data['pagg'] .= '<li><a href="#" id="next">Следующая</a></li></ul>';
        }
        return $data;
    }

    public function saveYearPays($data)
    {
        for ($i=0;$i<count($data['id']);$i++){
            $ins=['pay'=>$data['year'][$i]];
            $this->db->where('id',$data['id'][$i]);
            $this->db->update('dancers',$ins);
        }
        return true;
    }

    public function showStat($style_id)
    {
        $year=date('Y',time());
        /*$q = $this->db->query('select u.first_name, u.last_name, u.father_name, ci.city, cu.title,'
                . ' d.birthdate, s.style,'
                . ' (select sum(points) from comp_list where dancer_id=d.id) as sum_p, t.id as trainer_id,'
                . ' (select users.last_name from users, trainers where trainers.id=t.id and trainers.user_id=users.id) as t_last_name,'
                . ' (select users.first_name from users, trainers where trainers.id=t.id and trainers.user_id=users.id) as t_first_name,'
                . ' (select users.father_name  from users, trainers where trainers.id=t.id and trainers.user_id=users.id) as t_father_name'
                . ' from users as u, dancers as d, trainers as t, clubers as cu, styles s,'
                . ' comp_list as cl, competitions as co, cities as ci'
                . ' where co.date_close>'.$year.''
                . ' and cl.comp_id=co.id and d.user_id=u.id and d.trainer_id=t.id'
                . ' and t.club_id=cu.id and cu.city_id=ci.id and s.id=cl.style_id'
                . ' and d.id=cl.dancer_id'
                . ' and cl.style_id='.$style_id
                . ' group by d.id'
                . ' order by sum_p desc');*/
        $q = $this->db->query('select d.id, u.first_name, u.last_name, u.father_name, ci.city, cu.title,'
                . ' d.birthdate, s.style,'
                . ' cl.points,'
                . ' (select users.last_name from users, trainers where trainers.id=t.id and trainers.user_id=users.id) as t_last_name,'
                . ' (select users.first_name from users, trainers where trainers.id=t.id and trainers.user_id=users.id) as t_first_name,'
                . ' (select users.father_name  from users, trainers where trainers.id=t.id and trainers.user_id=users.id) as t_father_name'
                . ' from users as u, dancers as d, trainers as t, clubers as cu, styles s,'
                . ' comp_list as cl, competitions as co, cities as ci'
                . ' where co.date_close>'.$year.' and d.id=cl.dancer_id'
                . ' and cl.comp_id=co.id and d.user_id=u.id and d.trainer_id=t.id'
                . ' and t.club_id=cu.id and cu.city_id=ci.id and s.id=cl.style_id'
                . ' and cl.style_id='.$style_id);

        $result = $q->result_array();
        $res=[];
        foreach ($result as $r){
            if ($r['points']>0){
                $res[] = $r;
            }
        }
        $f=[];
        for($i=0;$i<count($res);$i++){
            $new = true;
            for($k=0;$k<count($f);$k++){
                if($res[$i]['id']==$f[$k]['id'] && $res[$i]['style']==$f[$k]['style']){
                    $new=false;
                    $f[$k]['points']=$f[$k]['points']+$res[$i]['points'];
                }
            }
            if ($new==true){
                $f[]=$res[$i];
            }
        }
        for($i=0;$i<count($f);$i++){
            $max = 0;
            for($k=0;$k<count($f);$k++){
                if ($f[$i]['points']>$f[$k]['points']){
                    $val=$f[$i];
                    $f[$i]=$f[$k];
                    $f[$k]=$val;
                }
            }
        }

        $html='';
        foreach ($f as $r){
            $html.='<tr>';
            $html.='<td>'.$r['last_name'].' '.$r['first_name'].' '.$r['father_name'].'</td>';
            $html.='<td>'.$r['city'].'</td>';
            $html.='<td>'.$r['title'].'</td>';
            $html.='<td>'.$r['t_last_name'].' '.$r['t_first_name'].' '.$r['t_father_name'].'</td>';
            $html.='<td>'.substr($r['birthdate'],0,4).'</td>';
            $html.='<td>'.$r['style'].'</td>';
            $html.='<td>'.$r['points'].'</td>';
            $html.='</tr>';
        }
        return $html;
    }

    public function selectTrainers($club_id)
    {
        $q = $this->db->query('select u.first_name, u.last_name, u.father_name, t.id'
                . ' from trainers t, users u'
                . ' where t.user_id=u.id and t.club_id='.$club_id);
        $row = $q->result_array();
        $html='<option value="0"> Выберите тренера</option>';
        foreach ($row as $r){
            $html.='<option value='.$r['id'].'>'.$r['last_name'].' '.$r['first_name'].' '.$r['father_name'];
        }
        return $html;
    }

    public function selectDancers($trainer_id)
    {

        $q = $this->db->query('select u.first_name, u.last_name, u.father_name, d.id, d.birthdate'
                . ' from users u, dancers d'
                . ' where d.user_id=u.id and  d.trainer_id='.$trainer_id);
        $row = $q->result_array();
        $html='';
        foreach ($row as $r){
            $html.='<tr><td>'.$r['last_name'].' '.$r['first_name'].' '.$r['father_name'];
            $ytime = time() - strtotime($r['birthdate']);
            $year = ($ytime - $ytime % 31556926) / 31556926;
            $html.='</td><td>'.$year;
            //$html.='</td><td><input type="checkbox" name="dancers[]" value='.$r['id'].'>';
            $html.='</td></tr>';
        }
        return $html;
    }

    public function delPart($part)
    {
        $q = $this->db->where('part', $part);
        $q = $this->db->delete('comp_list');
        return true;
    }

    public function getCSVlist($list, $comp_id, $role = 'admin'){
        $files = [];
        if (count($list)==0) return $files;
        $n = 0;//счетчик числа файлов
        if ($role == 'admin'){
            $files[0] = [];
            $files[0]['name'] = 'Всё';
            $name =  'list_'.$this->session->id.'_'.$comp_id.'_0';
            $files[0]['file'] = $this->makeCSV($list, $name);
        }
        $dan = implode(',', array_unique(array_column($list, 'id')));
        $q = $this->db->query('select DISTINCT c.title, t.club_id, d.trainer_id, d.id,'
                . ' u.first_name, u.last_name, u.father_name'
                . ' from clubers c, trainers t, users u, dancers d'
                . ' where d.trainer_id=t.id and t.club_id=c.id and t.user_id=u.id'
                . ' and d.id in ('.$dan.')');
        $club_list = $q->result_array();//получаем весь массив тренеров с клубами
        $clubs = array_unique(array_column($club_list, 'club_id'));//получаем только id КЛУБОВ
        $i = 0;
        $club = [];//массив со списком тренеров по клубам
        foreach ($clubs as $cl){
            $club[$i]=[];
            $club[$i]['club_id'] = $cl;
            $club[$i]['trainers']=[];
            foreach ($club_list as $clist){
                if ($cl == $clist['club_id']){
                    $club[$i]['title'] = $clist['title'];
                    $trainers = array_column($club[$i]['trainers'], 'trainer_id');
                    if (!in_array($clist['trainer_id'], $trainers)){
                        $club[$i]['trainers'][]=['trainer_id'=>$clist['trainer_id'],
                        'trainer_name'=>$clist['last_name'].' '.$clist['first_name'].' '.$clist['father_name'],
                        'dancers'=>[]];
                    }

                }
            }
            $i++;
        }//завершено создание массива тренеров по клубам
        foreach ($club as $k=>$cl){
            foreach ($cl['trainers'] as $t=>$tr){
                foreach ($club_list as $clist){
                    if ($clist['trainer_id'] == $tr['trainer_id']){
                        if (!in_array($clist['id'], $club[$k]['trainers'][$t]['dancers'])){
                            $club[$k]['trainers'][$t]['dancers'][] = $clist['id'];
                        }
                    }
                }
            }
        }//добавили к тренерам танцоров
        foreach ($club as $cl){//перебираем все клубы
            if ($role == 'admin' || $role == 'cluber'){
                $arr=[];//временный массив для передачи в файл
                foreach ($list as $l){
                    $find = false;
                    foreach ($cl['trainers'] as $tr){
                        foreach ($tr['dancers'] as $dan){
                            if ($l['id'] == $dan){
                                $find = true;
                            }
                        }
                    }
                    if ($find) {
                        $arr[] = $l;
                    }
                }//окончание создания временного масива для клуба
                $n++;
                $files[$n] = [];
                $files[$n]['name'] = 'Клуб '.$cl['title'];
                $name =  'list_'.$this->session->id.'_'.$comp_id.'_'.$n;
                $files[$n]['file'] = $this->makeCSV($arr, $name);
            }
            foreach ($cl['trainers'] as $tr){//создаём списки по тренерам
                $arr=[];//временный массив для передачи в файл
                foreach ($list as $l){
                    $find = false;
                    foreach ($tr['dancers'] as $dan){
                        if ($l['id'] == $dan){
                            $find = true;
                        }
                    }
                    if ($find) {
                        $arr[] = $l;
                    }
                }//окончание создания временного масива для тренера
                $n++;
                $files[$n] = [];
                $files[$n]['name'] = 'Клуб '.$cl['title'].' Тренер '.$tr['trainer_name'];
                $name =  'list_'.$this->session->id.'_'.$comp_id.'_'.$n;
                $files[$n]['file'] = $this->makeCSV($arr, $name);
            }
        }
        return $files;
    }

    function makeCSV($arr, $name){
        $sum = 0;
        foreach ($arr as $a){
            $sum += $a['Взнос грн.'];
        }
        $file = 'csv/' . $name . '.csv';
        $html = '';
        if (count($arr)==0) return false;
        $keys = array_keys($arr[0]);
        $first = true;
        foreach ($keys as $k){
            if ($k!='id'){
            if (!$first){
                    $html .= ';';
                } else {
                    $first = false;
                }
                $html .= $k;
            }
        }
        $html .= "\r\n";
        foreach ($arr as $row){

            $first = true;
            foreach ($row as $k=>$r){
                if ($k!='id'){
                if (!$first){
                    $html .= ';';
                } else {
                    $first = false;
                }
                $html .= $r;
                }
            }
            $html .= "\r\n";

        }
        $html .= 'ИТОГО:;'.$sum.'; грн.';
        $h = fopen($file, "w");
        fwrite($h, iconv('UTF-8','Windows-1251',$html));
        fclose($h);
        return $file;
    }

    public function danExp($type, $id){
        switch($type){
            case 'user':
                $sel = 'select w.way, l.name as lig, e.points'
                    . ' from ways w, ligs l, experience e'
                    . ' where e.lig_id=l.id and e.way_id=w.id'
                    . ' and e.dancer_id=(select id from dancers where user_id='.$id.')';
                break;
            case 'dancer':
                $sel = 'select w.way, l.name as lig, e.points'
                    . ' from ways w, ligs l, experience e'
                    . ' where e.lig_id=l.id and e.way_id=w.id'
                    . ' and e.dancer_id='.$id;
                break;
        }
        $res = $q->result_array();
        $q = $this->db->query($sel);
        return $res;
    }

    public function danExpHtml($type, $id){
        $exp = $this->danExp($type, $id);
        $html='<ul>';
        foreach($exp as $e){
            $html.='<li><strong>'.$e['way'].'</strong> '.$e['lig'].' <span class="badge">'.$e['points'].'</span></li>';
        }
        $html.='</ul>';
        return $html;
    }

    public function getTrainerListForCsv($trainer_id, $comp_id)
    {
        $sel = 'select cl.print_number, u.last_name, u.first_name, cl.lig_id, cl.count_id,'
            . ' d.birthdate, cl.points, s.style, b.name as bell_name, t.id as trainer_id, '
            . ' cl.comp_id, ca.name as cat_age, cc.name as count_name, b.type as bell_type,'
            . ' (select name from ligs where ligs.id=cl.lig_id) as comp_lig,'
            . ' (select ligs.name from ligs, experience, competitions '
                    . ' where ligs.id=experience.lig_id and experience.dancer_id=d.id'
                    . ' and competitions.way_id=experience.way_id and competitions.id=cl.comp_id) as dancer_lig'
            . ' from comp_list cl, dancers d, users u, trainers t, styles s,'
            . ' cat_age ca, cat_count cc, bellydance b'
            . ' where cl.dancer_id=d.id and d.user_id=u.id'
            . ' and d.trainer_id=t.id and t.id=' . $trainer_id
            . ' and cl.comp_id=' . $comp_id
            . ' and cl.style_id=s.id'
            . ' and cl.age_id=ca.id and cl.count_id=cc.id and d.bell_id=b.id';
        $q = $this->db->query($sel);
        $list = $q->result_array();
        return $this->addPaysForCsv($list);
    }

    public function addPaysForCsv($list)
    {
        for ($i=0; $i < count($list); $i++) {
            $sel = 'select pay_not, pay_iude, pay_other'
                .' from pays'
                . " where comp_id={$list[$i]['comp_id']} and count_id={$list[$i]['count_id']} and lig_id={$list[$i]['lig_id']}";
            $q = $this->db->query($sel);
            $res = $q->result_array();
            if ($list[$i]['bell_type'] == 1) {
                $list[$i]['pay'] = $res[0]['pay_iude'];
            } elseif ($list[$i]['bell_type'] == 1) {
                $list[$i]['pay'] = $res[0]['pay_other'];
            } else {
                $list[$i]['pay'] = $res[0]['pay_not'];
            }
        }
        return $list;
    }

    public function getCluberListForCsv($cluber_id, $comp_id)
    {
        $sel = 'select cl.print_number, u.last_name, u.first_name,'
            . ' d.birthdate, cl.points, s.style, b.name as bell_name, t.id as trainer_id, clubers.id as club_id,'
            . ' cl.comp_id, cl.count_id, cl.lig_id, b.type as bell_type, ca.name as cat_age, cc.name as count_name,'
            . ' (select name from ligs where ligs.id=cl.lig_id) as comp_lig,'
            . ' (select ligs.name from ligs, experience, competitions '
                    . ' where ligs.id=experience.lig_id and experience.dancer_id=d.id'
                    . ' and competitions.way_id=experience.way_id and competitions.id=cl.comp_id) as dancer_lig'
            . ' from comp_list cl, dancers d, users u, trainers t, styles s,'
            . ' cat_age ca, cat_count cc, bellydance b, clubers'
            . ' where cl.dancer_id=d.id and d.user_id=u.id'
            . ' and d.trainer_id=t.id and t.club_id=clubers.id and clubers.id=' . $cluber_id
            . ' and cl.comp_id=' . $comp_id
            . ' and cl.style_id=s.id'
            . ' and cl.age_id=ca.id and cl.count_id=cc.id and d.bell_id=b.id';
        $q = $this->db->query($sel);
        $list = $q->result_array();
        return $this->addPaysForCsv($list);
    }

    function getCompInfoForCsv($comp_id)
    {
        $sel = 'select co.name as comp_name, ci.city, co.comment'
            . ' from competitions co, cities ci'
            . ' where co.city_id=ci.id and co.id=' . $comp_id;
        $q = $this->db->query($sel);
        $res = $q->result_array();
        $info = $res[0];
        $sel = 'select DISTINCT dancer_id from comp_list where comp_id=' . $comp_id;
        $q = $this->db->query($sel);
        $info['count'] = count($q->result_array());
        return $info;
    }

    function getTrainerInfoForCsv($trainer_id, $comp_id)
    {
        $info = [];
        $sel = 'select u.first_name, u.last_name, u.email, u.phone'
            . ' from users u, trainers t'
            . ' where u.id=t.user_id and t.id=' . $trainer_id;
        $q = $this->db->query($sel);
        $res = $q->result_array();
        $info = $res[0];
        $sel = 'select DISTINCT cl.dancer_id'
            . ' from comp_list cl, dancers d, trainers t'
            . ' where cl.dancer_id=d.id and d.trainer_id=t.id'
            . ' and cl.comp_id=' . $comp_id . ' and t.id=' . $trainer_id;
        $q = $this->db->query($sel);
        $info['count'] = count($q->result_array());
        return $info;
    }

    function getCluberInfoForCsv($clubers_id, $comp_id)
    {
        $info = [];
        $sel = 'select u.first_name, u.last_name, u.email, u.phone, ci.city, clubers.title, clubers.id'
            . ' from users u, clubers, cities ci'
            . ' where u.id=clubers.user_id and ci.id=clubers.city_id and clubers.id=' . $clubers_id;
        $q = $this->db->query($sel);
        $res = $q->result_array();
        $info = $res[0];
        $sel = 'select DISTINCT cl.dancer_id'
            . ' from comp_list cl, dancers d, trainers t'
            . ' where cl.dancer_id=d.id and d.trainer_id=t.id'
            . ' and cl.comp_id=' . $comp_id . ' and t.club_id=' . $clubers_id;
        $q = $this->db->query($sel);
        $info['count'] = count($q->result_array());
        return $info;
    }

    public function makeTrainerCSV($trainer_id, $comp_id)
    {
        $text = '';
        $comp_info = $this->getCompInfoForCsv($comp_id);
        $text .= 'Данные конкурса (';
        $text .= $comp_info['comp_name'] . ', ';
        $text .= $comp_info['city'] . ', ';
        $text .= $comp_info['comment'] . ")\r\n";
        $t_i = $this->getTrainerInfoForCsv($trainer_id, $comp_id);
        $text .= 'Тренер ';
        $text .= $t_i['last_name'] . ' ' . $t_i['first_name'] . "\r\n";
        $text .= 'Телефон тренера ' . $t_i['phone'] . "\r\n";
        $text .= 'Почта тренера ' . $t_i['email'] . "\r\n";
        $text .= 'Кол-во участников ' . $t_i['count'] . "\r\n";
        $dancers = $this->getTrainerListForCsv($trainer_id, $comp_id);
        $text .= $this->tableForCSV($dancers);
        return $text;
    }

    public function makeCluberCSV($cluber_id, $comp_id)
    {
        $text = '';
        $comp_info = $this->getCompInfoForCsv($comp_id);
        $text .= 'Данные конкурса (';
        $text .= $comp_info['comp_name'] . ', ';
        $text .= $comp_info['city'] . ', ';
        $text .= $comp_info['comment'] . ")\r\n";
        $c_i = $this->getCluberInfoForCsv($cluber_id, $comp_id);
        $text .= $c_i['city'] . ', ' . $c_i['title'] . "\r\n";
        $text .= 'Руководитель ' . $c_i['last_name'] . ' ' . $c_i['first_name'] . "\r\n";
        $text .= 'Кол-во участников ' . $c_i['count'] . "\r\n";
        $dancers = $this->getCluberListForCsv($cluber_id, $comp_id);
        if (count($dancers) == 0) return false;
        $text .= $this->tableForCSV($dancers);
        return $text;
    }

    public function tableForCSV($dancers)
    {
        $text = "№ участника (печать);Фамилия, Имя Танцора;Год рождения;Лига танцора (реальная);Баллы;Стиль;Возраст;Кол-во;Лига участия;Организация;Взнос \r\n";
        foreach ($dancers as $d) {
            $text .= $d['print_number'] . ";";
            $text .= $d['last_name'] . ' ' . $d['first_name'] . ";";
            $text .= substr($d['birthdate'], 0, 4) . ";";
            $text .= $d['dancer_lig'] . ";";
            $text .= $d['points'] . ";";
            $text .= $d['style'] . ";";
            $text .= $d['cat_age'] . ";";
            $text .= $d['count_name'] . ";";
            $text .= $d['comp_lig'] . ";";
            $text .= $d['bell_name'] . ";";
            $text .= $d['pay'];
            $text .= "\r\n";
        }
        return $text;
    }

    public function genTrainerCsvFiles($comp_id)
    {
        $comp_info = $this->getCompInfoForCsv($comp_id);
        $trainer_id = $this->getTrainerId($this->session->id);
        $html = $this->makeTrainerCSV($trainer_id, $comp_id);
        $file = [];
        $file['name'] = 'Конкурс ' . $comp_info['comp_name'];
        $name = 'list_' . $this->session->id . '_' . $comp_id;
        $file['file'] = 'csv/' . $name . '.csv';
        $h = fopen($file['file'], "w");
        fwrite($h, iconv('UTF-8', 'Windows-1251', $html));
        fclose($h);
        return $file;
    }

    public function genCluberCsvFiles($comp_id)
    {
        $cluber_id = $this->getClubId($this->session->id);
        $club_info = $this->getCluberInfoForCsv($cluber_id, $comp_id);
        $comp_info = $this->getCompInfoForCsv($comp_id);
        $files = [];
        $html = $this->makeCluberCSV($cluber_id, $comp_id);
        if (!$html) return $files;
        $i = 0;
        $files[$i]['name'] = 'Руководитель ' . $club_info['last_name'] . ' ' . $club_info['first_name'] . ' Конкурс ' . $comp_info['comp_name'];
        $name =  'list_'.$this->session->id.'_'.$comp_id;
        $files[$i]['file'] = 'csv/' . $name . '.csv';
        $h = fopen($files[$i]['file'], "w");
        fwrite($h, iconv('UTF-8','Windows-1251', $html));
        fclose($h);
        $trainers = $this->getTrainersComp($comp_id, $cluber_id);
        foreach ($trainers as $trainer) {
            $i++;
            $files[$i]['name'] = 'Тренер ' . $trainer['last_name'] . ' Конкурс №' . $comp_id;
            $name =  'list_'.$this->session->id . '_' . $trainer['id'] . '_' . $comp_id;
            $files[$i]['file'] = 'csv/' . $name . '.csv';
            $html = $this->makeTrainerCSV($trainer['id'], $comp_id);
            $h = fopen($files[$i]['file'], "w");
            fwrite($h, iconv('UTF-8','Windows-1251', $html));
            fclose($h);
        }
        return $files;
    }

    public function getTrainersComp($comp_id, $club_id)
    {
        $sel = 'select DISTINCT u.first_name, u.last_name, t.id'
            . ' from users u, trainers t, comp_list cl, dancers d'
            . ' where cl.dancer_id=d.id and d.trainer_id=t.id and t.user_id=u.id'
            . ' and cl.comp_id=' . $comp_id
            . ' and t.club_id=' . $club_id;
        $q = $this->db->query($sel);
        $res = $q->result_array();
        return $res;
    }
}
