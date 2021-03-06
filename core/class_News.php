<?php
class BoardView {
	public $db_board_table  = 'board';
	public $db_user_table   = 'users';
	public $db_role_table   = 'spr_role_users';
	public $db_conf_table   = 'spr_conference';
	public $db_voice_table  = 'voice_chat';
	public $csv_file        = 'inf/report.csv';
	public $db_group_users  = 'spr_group_users';
    public $db_group_role   = 'spr_group_role';
	public  static $DB;

	public function __construct() {
		self::$DB = new DB();
	}
	public function __destruct() {
		self::$DB->close();
	}
	
	function ViewNews(){
				$arr_news = array ();
				$sql = "SELECT id,text FROM $this->db_board_table ORDER BY id DESC ";
				$qw=self::$DB->query($sql);
					if (self::$DB->num_rows($qw)>0) {
						while($re = self::$DB->fetch_array($qw)){
							$id = $re['id'];
							$arr_news[$id] .= $re['text'];
						}
					}			
					return $arr_news;
		}
	
	function AddNewsBoard($text){
			//$text = self::$DB->escape($text);
			$dat = date('Y-m-d H:i:s');
			$sql = "INSERT INTO $this->db_board_table (text) VALUES ('$text')";
			$qw=self::$DB->query($sql);
			if($sql==true){
			$sql = "SELECT id,text FROM $this->db_board_table ORDER BY id DESC";
			$qw=self::$DB->query($sql);
				if (self::$DB->num_rows($qw)>0) {
					$i_count = self::$DB->num_rows($qw);
					while($re = self::$DB->fetch_array($qw)){
						$text_news_view .= "<tr class='text_news'>
												<td align='center' width='50'>
												<a href='#' onClick='edit_news($re[id]);'><img src='images/edit.png' /></a> 
												<a href='#' onClick='del_news($re[id]);'><img src='images/cross.png' /></a>
												<td align='left' width='110'><span>Объявление №$i_count</span></td>
												<td align='left' >$re[text]</td>
												</tr>";
						$i_count= $i_count - 1;	
					}
				}
				return $text_news_view;
				}else{
					return '-1';
			}
		}
	function ViewUsers(){
			$sql = "SELECT * FROM $this->db_user_table WHERE role IN (1,2) ORDER BY id DESC";
			$qw=self::$DB->query($sql);
				if (self::$DB->num_rows($qw)>0) {
					while($re = self::$DB->fetch_array($qw)){
						if($re['role'] == 1){
							$role = 'Администратор';
							}else{
							$role = 'Пользователь';
						}
 						if($re['closed'] == 1){
							$closed = '<img src="images/lock.png" />';
							$sel_check = 'checked';
							}else{
							$closed = '';
							$sel_check = '';
						}
 						if($re['moderation'] == 1){
							$sel_check_m = 'checked';
							}else{
							$sel_check_m = '';
						}

						if($re['dat_ses']!=''){
							//$last_dat_chat = date("d-m-Y H:i:s",strtotime("$re[dat_ses]"));
							$last_dat_chat = date("Y-m-d",strtotime("$re[dat_ses]"));
							}else{
							$last_dat_chat ='';
						}
						if($re['dat_reg']!=''){
							//$reg_dat_chat = date("d-m-Y H:i:s",strtotime("$re[dat_reg]"));
							$reg_dat_chat = date("Y-m-d",strtotime("$re[dat_reg]"));
						}else{
							$reg_dat_chat ='';
						}
						$text_user_view .= "
							 <tr class='tabluser_view'>
								<td align='center'>
								<input $sel_check class='check_chat' name='metka[]' type='checkbox' value='$re[id]'>
								</td>
								<td align='center'>
								<input $sel_check_m class='check_chat_m' name='metka_m[]' type='checkbox' value='$re[id]'>
								</td>
								<td align='left'>$closed 
								<a href='#' onClick='edit_user($re[id]); return false;'>$re[name]</a>
								</td>
								<td align='left'>$re[fio]</td>
								<td align='left'>$re[phone]</td>
								<td align='left'>$re[upass]</td>
								<td align='left'>$re[mail]</td>
								<td align='left'>$reg_dat_chat</td>
								<td align='left'>$re[ip_reg]</td>
								<td align='left'>$re[country_reg]</td>
								<td align='left'>$re[city_reg]</td>
								<td align='left'>$last_dat_chat</td>
                                                                <td align='center'>$name_group</td>
								<td align='center'>$role</td>
								<td align='center'>
									<a href='#' onClick='del_user($re[id]);'>
									<img src='images/cross.png' width='16' height='16' />
									</a>
								</td>
							</tr>";
					}
				}			
					return $text_user_view;
	}
	
	function ViewUsers_jq($sidx,$sord,$page,$limit, $group_flag = false, $id_group = false){
				//$page = 1;      // Номер запришиваемой страницы
				//$limit = 100;   // Количество запрашиваемых записей
                $view_only_activ = "&& id_group = (SELECT id_group FROM $this->db_group_users WHERE activ = '1')";
                if($id_group != false ) $view_only_activ = "&& id_group = '$id_group'";
                if($group_flag) $view_only_activ = "";
				if(!$sidx) $sidx = 'dat_reg';	// Если не указано поле сортировки, то производить сортировку по первому полю
					//суммарное кол-во записей в таблице
					$sql = "SELECT COUNT(*) AS count FROM $this->db_user_table WHERE role IN (1,2,4) "
                                                . " $view_only_activ"
                                                . " ORDER BY id DESC";
					$qw=self::$DB->query($sql);
					$re = self::$DB->fetch_array($qw);
					$count = $re['count'];    // Теперь эта переменная хранит кол-во записей в таблице
					
					if( $count > 0 && $limit > 0) {
							$total_pages = ceil($count/$limit);
						} else {
							$total_pages = 0;
						}
						// Если по каким-то причинам клиент запросил
						if ($page > $total_pages) $page=$total_pages;

						// Рассчитываем стартовое значение для LIMIT запроса
						$start = $limit*$page - $limit;
						// Зашита от отрицательного значения
					if($start <0) $start = 0;

					$data->page       = $page;
					$data->total      = $total_pages;
					$data->records    = $count;
					// Строки данных для таблицы
					$i = 0;
					$sql = "SELECT * FROM $this->db_user_table WHERE role IN (1,2,4)"
                                                . "$view_only_activ"
                                                . " ORDER BY $sidx $sord LIMIT $start , $limit";
					$qw=self::$DB->query($sql);
					
					while($row = self::$DB->fetch_array($qw)) {
 						if($row['closed'] == 1){
							$closed = '<img src="images/lock.png" />';
							}else{
							$closed = '';
						}
 						if($row['moderation'] == 1){
							$moder = '<img src="images/user1.gif" />';
							}else{
							$moder = '';
						}
						if($row['dat_ses']!=''){
							//$last_dat_chat = date("d-m-Y H:i:s",strtotime("$re[dat_ses]"));
							$last_dat_chat = date("Y-m-d",strtotime("$row[dat_ses]"));
							}else{
							$last_dat_chat ='';
						}
						if($row['dat_reg']!=''){
							//$reg_dat_chat = date("d-m-Y H:i:s",strtotime("$re[dat_reg]"));
							$reg_dat_chat = date("Y-m-d",strtotime("$row[dat_reg]"));
						}else{
							$reg_dat_chat ='';
						}

							$sql1 = "SELECT name_role FROM $this->db_role_table WHERE type = '$row[role]' ";
							$qw1=self::$DB->query($sql1);
							$rw = self::$DB->fetch_array($qw1);

                                                        $sql2 = "SELECT name_group FROM $this->db_group_users WHERE id_group = '$row[id_group]' ";
							$qw2=self::$DB->query($sql2);
							$rw_ = self::$DB->fetch_array($qw2);

							$name = "<a href='#' onClick='edit_user($row[id]); return false;'>$row[name]</a>";
							$data->rows[$i]['id'] = $row[id];
							$data->rows[$i]['cell'] = array($closed,$moder,$name,$row[fio],$row[phone],$row[upass],$row[mail],$reg_dat_chat,$row[ip_reg],$row[country_reg],$row[city_reg],$last_dat_chat,$rw_[name_group],$rw[name_role]);
							$i++;
					}
					return  json_encode($data);
	}

	
		function ViewUsersOnline(){
			$sql = "SELECT * FROM $this->db_user_table 
										WHERE ses!='0' ORDER BY id DESC";
			$qw=self::$DB->query($sql);
			$text_user_view ='';
			$num = self::$DB->num_rows($qw);
				if ($num > 0) {
					while($re = self::$DB->fetch_array($qw)){
						if($re['role'] == 1){
							$role = 'Администратор';
							}else{
							$role = 'Пользователь';
						}
						if($re['closed'] == 1){
							$closed = '<img src="images/lock.png" />';
							}else{
							$closed = '';
						}
						if($re['dat_ses']!=''){
							//$last_dat_chat = date("d-m-Y H:i:s",strtotime("$re[dat_ses]"));
							$last_dat_chat = date("Y-m-d",strtotime("$re[dat_ses]"));
							}else{
							$last_dat_chat ='';
						}
						if($re['dat_reg']!=''){
							//$reg_dat_chat = date("d-m-Y H:i:s",strtotime("$re[dat_reg]"));
							$reg_dat_chat = date("Y-m-d",strtotime("$re[dat_reg]"));
						}else{
							$reg_dat_chat ='';
						}
						
						$text_user_view .= "
							 <tr class='tabluser_view'>
								<td align='left'>$num</td>
								<td align='left'>$closed $re[name] </td>
								<td align='left'>$re[fio]</td>
								<td align='left'>$reg_dat_chat</td>
								<td align='left'>$last_dat_chat</td>
								<td align='left'>$re[ip]</td>
								<td align='left'>$re[country]</td>
								<td align='left'>$re[city]</td>
							</tr>";
							$num = $num-1;
					}
				}			
					return $text_user_view;
	}

	function ClosedConference($cl){
			$sql = "UPDATE $this->db_voice_table SET closed_chat  ='$cl' WHERE id='1'  ";
			$qw=self::$DB->query($sql);
			if($sql==true){
					return 1;
				}else{
					return '-1';
			}
	}		
	function CheckAllConference($checkConf){
			if($checkConf == 'dropChat'){
				$sql = "UPDATE $this->db_user_table SET ses  ='0' WHERE role ='2'  ";
				$qw=self::$DB->query($sql);
				$sql = "DELETE FROM $this->db_user_table WHERE role ='3'  ";
				$qw=self::$DB->query($sql);
				$sql = "UPDATE $this->db_voice_table SET status_chat  ='0',closed_chat='0' WHERE id ='1'  ";
				$qw=self::$DB->query($sql);
			}
			if($checkConf == 'createChat'){
				$sql = "UPDATE $this->db_voice_table SET status_chat   ='1' WHERE id ='1'  ";
				$qw=self::$DB->query($sql);
			}
			if($sql==true){
					return 1;
				}else{
					return '-1';
			}
	}		
	function UserClosed($user_name,$kind){//в API $obj->{'comand'} == "logoutUser") выход пользователя из чата (и по таймауту тоже)
			if($kind!=1){//такой выход т.к. почему то $kind передается или 1 или пусто
				$mod = '1';
				}else{
				$mod = '0';
			}
			/*
			$sql = "UPDATE $this->db_user_table SET debagname='$user_name' WHERE id ='283'  ";
			$qw1=self::$DB->query($sql);
			*/
			$sql = "SELECT role FROM $this->db_user_table WHERE name='$user_name' ";
			$qw=self::$DB->query($sql);
				if (self::$DB->num_rows($qw)>0) {
					$re = self::$DB->fetch_array($qw);
					if($re['role']!=3){
						$sql = "UPDATE $this->db_user_table SET ses = '0',moderation = '$mod' WHERE name='$user_name'  ";
						$qw=self::$DB->query($sql);
						}else{
							$sql = "DELETE FROM $this->db_user_table WHERE name='$user_name'  ";
							$qw=self::$DB->query($sql);
					}
				}
				return 1;
	}	
	function ConfUsersChat(){
			$sql = "SELECT closed_chat,status_chat FROM $this->db_voice_table WHERE id = '1' ";
			$qw=self::$DB->query($sql);
				if (self::$DB->num_rows($qw)>0) {
					$re = self::$DB->fetch_array($qw);
					if($re['status_chat']>0){
						$sql_ = "SELECT name_conf FROM $this->db_conf_table WHERE type = '$re[closed_chat]' ";
						$qw_=self::$DB->query($sql_);
						$rw = self::$DB->fetch_array($qw_);
						$v_userconf = $rw['name_conf'];  
					}else{
						$v_userconf = ''; 
					}
				}
				return $v_userconf;
	}			
	function getCSV(){
		if (file_exists($this->csv_file)) { //Если файл существует
			$hand = fopen($this->csv_file, 'w');
			$sql = "SELECT us.name, us.fio, us.phone, us.upass, us.mail, us.dat_reg, us.ip_reg, us.country_reg, us.city_reg, us.dat_reg, gr.name_group as name_group
							FROM $this->db_user_table as us LEFT JOIN $this->db_group_users AS gr ON us.id_group = gr.id_group";
			$qw=self::$DB->query($sql);
			$text_file .=iconv("utf-8", "windows-1251","Логин;Фио;Номер телефона;Пароль;E-mail;Дата регистрации;IP регистрации;Страна регистрации;Город регистрации;Дата регистрации;Группа;\r\n");
				while($re = self::$DB->fetch_array($qw)){
					$text_file .= iconv("utf-8", "windows-1251", $re['name']).';'.iconv("utf-8", "windows-1251", $re['fio']).';="'.iconv("utf-8", "windows-1251", $re['phone']).'";="'.iconv("utf-8", "windows-1251", $re['upass']).'";'.iconv("utf-8", "windows-1251", $re['mail']).';'.iconv("utf-8", "windows-1251",$re['dat_reg']).';'.iconv("utf-8", "windows-1251",$re['ip_reg']).';'.iconv("utf-8", "windows-1251",$re['country_reg']).';'.iconv("utf-8", "windows-1251",$re['city_reg']).';'.iconv("utf-8", "windows-1251",$re['dat_reg']).';'.iconv("utf-8", "windows-1251",$re['name_group']).";\r\n";
				}
					fwrite($hand, $text_file);
					fclose($hand);
        }else{
			echo 'File not found';
		}
				return 1;
	}		
	function regUsersCount($type, $group_flag = false) {
                $view_only_activ = "&& id_group = (SELECT id_group FROM $this->db_group_users WHERE activ = '1')";
                if($group_flag > 0) $view_only_activ = "";
		if($type == 1){//count registration users
			$sql = "SELECT count(id) as id FROM $this->db_user_table WHERE role IN (1,2,4) $view_only_activ";
			}else{//count registration and moderations users
			$sql = "SELECT count(id) as id FROM $this->db_user_table WHERE moderation = 0 && role IN (1,2,4) $view_only_activ";
		}
			$qw=self::$DB->query($sql);
			$re = self::$DB->fetch_array($qw);
			return $re['id'];
	}
	function adminViewChat($role) {//check for admin and speaker open confernce
			$sql = "SELECT status_chat FROM $this->db_voice_table WHERE id='1' ";
			$qw=self::$DB->query($sql);
			if(self::$DB->num_rows($qw)>0){
				$re = self::$DB->fetch_array($qw);
				if($re['status_chat'] == 1){
						switch($role){
							case 1:
								$role = 11;
								break;
							case 4:
								$role = 41;
								break;
						}
					}
				}
			return $role;
	}
        
        function activGroupUsers(){
                        $view = ''; 
			$sql = "SELECT name_group FROM $this->db_group_users WHERE activ = '1'";
			$qw=self::$DB->query($sql);
                        if ( self::$DB->num_rows($qw) > 0 ){
                            $re = self::$DB->fetch_array($qw);
                            $view = $re['name_group'];
                        }			
			return $view;
	}
        
        function ViewSelectGroup($flg = false){
                        $text_user_view ="<option value=''>Выберите группу...</option>";
			$sql = "SELECT * FROM $this->db_group_users ORDER BY id_group";
			$qw=self::$DB->query($sql);
                        if($flg) $text_user_view ="";

                        $num = self::$DB->num_rows($qw);
                        if ( $num > 0 ){
                            while($re = self::$DB->fetch_array($qw)){
                            $sel = '';
                            if($id_group == $re['id_group']) $sel = 'selected'; 
				$text_user_view .="<option $sel value='{$re['id_group']}'>{$re['name_group']}</option>";
                            }
			}			
			return $text_user_view;
	}

        function addGroupUsers($name, $result = 0){
                $sql = "SELECT name_group FROM $this->db_group_users WHERE name_group = '$name'";
                $qw=self::$DB->query($sql);
                    if( self::$DB->num_rows($qw) == 0 ){
			$dat = date('Y-m-d H:i:s');
			$sql = "INSERT INTO $this->db_group_users (name_group) VALUES ('$name')";
			$qw=self::$DB->query($sql);
                        if( $qw == true ) $result = 1;
                        }else{
                        $result = 2;
                    }
                    return $result;
        }        
        function delGroupUsers($id_group, $result = 0){
                $sql = "SELECT activ FROM $this->db_group_users WHERE id_group = '$id_group' && activ = '1'";
                $qw=self::$DB->query($sql);
                    if( self::$DB->num_rows($qw) == 0 ){
                        $sql = "DELETE FROM $this->db_group_users WHERE id_group = '$id_group'";
                        $qw=self::$DB->query($sql);
                        if( $qw == true ) $result = 1;
                        }else{
                        $result = 2;
                    }
                return $result;   
        }
        
        function editGroupUsers($id_group, $group_name, $action){
                $result = array('result'=> 0);
                $result['name'] = $group_name;
                $set_action = '';

                //Check activ conference
            	$sql = "SELECT status_chat FROM $this->db_voice_table WHERE id='1' ";
		$qw=self::$DB->query($sql);
		if (self::$DB->num_rows($qw)>0) {
                    $re = self::$DB->fetch_array($qw);
                    $status_chat = $re['status_chat']; 
		}
                if($action == 1){//Setup action group
                    if($status_chat == 0){
         		$sql = "UPDATE $this->db_group_users SET activ = '0'";
                	$qw=self::$DB->query($sql);
                        $set_action =", activ = '$action'";
                        $result['result'] = 1;
                    }else{
                     $result['result'] = 3;    
                    }    
                }
                
 		$sql = "UPDATE $this->db_group_users SET name_group = '$group_name' $set_action WHERE id_group ='$id_group'";
		$qw = self::$DB->query($sql);
                if( $qw == 1 && $action != 1 ) $result['result'] = 2;
                return  json_encode($result);
        }
        
        function addUsersInGroup($group, $id){
            $sql = "UPDATE $this->db_user_table SET id_group  ='$group' WHERE id IN ($id) ";
            $qw=self::$DB->query($sql);
            return $qw;
        }
}
