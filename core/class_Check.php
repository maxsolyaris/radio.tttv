<?phprequire 'DB.php';class CheckView{	public $db_voice_table = 'voice_chat';	public $db_board_table = 'board';	public $db_users_table = 'users';        public $db_group_users  = 'spr_group_users';	public  static $DB;	public function __construct() {		self::$DB = new DB();	}	public function __destruct() {		self::$DB->close();	}		function CheckConference() {	 	$sql = "SELECT closed_chat,status_chat FROM $this->db_voice_table WHERE id='1' ";		$qw=self::$DB->query($sql);		if (self::$DB->num_rows($qw)>0) {			$re = self::$DB->fetch_array($qw);				if($re['status_chat'] == 0){					return 4;				}				/*if ($re['closed_chat']==1) {						$_SESSION['user_closed_chat'] = $re['closed_chat'];						return 1;					}else{					return 2;				}*/			return $re['closed_chat']; 						}else{			return '-1';		}	}	function UsersConfereceCheck($id,$close_chat){			$id = self::$DB->escape($id);			$close_chat = self::$DB->escape($close_chat);			if($close_chat == 1){				$close_chat = 1;				}else{				$close_chat = 0;			}			$sql = "UPDATE $this->db_users_table SET closed ='$close_chat' WHERE id IN ($id) ";			$qw=self::$DB->query($sql);			if($sql==true){					return 1;				}else{					return '-1';			}	}	function UsersConfereceModeration($id,$chat){			$id = self::$DB->escape($id);			$chat = self::$DB->escape($chat);			if($chat == 1){//open registration				$chat = 1;				}else{//closed registration				$chat = 0;			}			$sql = "UPDATE $this->db_users_table SET moderation ='$chat' WHERE id IN ($id) ";			$qw=self::$DB->query($sql);			$arr_id = explode(",", $id);			foreach($arr_id as $val){				$sql_u = "SELECT name,fio,upass,mail FROM $this->db_users_table WHERE id='$val' ";				$qw_u=self::$DB->query($sql_u);				$re = self::$DB->fetch_array($qw_u);				$this->OutMailreg($re['fio'],$re['mail'],$chat,$re['name'],$re['upass']);			}			if($sql==true){					return 1;				}else{					return '-1';			}	}	function EditViewUsers($id_user){			$sql = "SELECT * FROM $this->db_users_table WHERE id='$id_user' ";			$qw=self::$DB->query($sql);			$re = self::$DB->fetch_array($qw);                        $id_user_gr     = $re['id_group'];                         $id_user_role   = $re['role']; 				$sel_r[2] = '';				$sel_r[4] = '';				$sel_r[1] = '';				$sel_r[$re[role]] = 'selected';				$text_user_view = "				<tr>					<td width='120px' align='right'>					<strong>Логин:</strong>					</td>					<td  align='left'>					<input class='cforms_reg' type='text' name='name_edit' id='name_edit' value='$re[name]' />					</td>				</tr>				<tr>					<td width='120px' align='right'>					<strong>Имя(ФИО):</strong>					</td>					<td  align='left'>					<input class='cforms_reg' type='text' name='fio_edit' id='fio_edit' value='$re[fio]' />					</td>				</tr>				<tr>					<td width='120px' align='right'>					<strong>Телефон:</strong>					</td>					<td  align='left'>					<input class='cforms_reg' type='text' name='phone_edit' id='phone_edit' value='$re[phone]' />					</td>				</tr>				<tr>					<td align='right'>					<strong>e-mail:</strong>					</td>					<td align='left'>					<input class='cforms_reg'  type='text' name='mail_edit' id='mail_edit' value='$re[mail]' />					</td>				</tr>								<tr>					<td align='right'>					<strong>Новый пароль:</strong>					</td>					<td align='left'>					<input class='cforms_reg'  type='text' name='pass_edit' id='pass_edit'  />					</td>				</tr>				<tr>					<td align='right'>					<strong>Тип доступа:</strong>					</td>					<td align='left'>					<select class='cforms_sel'  name='sel_role_edit' id='sel_role_edit' >					";					$text_user_view .= "					<option $sel_r[2] value='2'>Пользователь</option>					<option $sel_r[4] value='4'>Speaker</option>					<option $sel_r[1] value='1'>Администратор</option>					</select>					</td>				</tr>				<tr>					<td align='right'>                                        <input name='id_edit' id='id_edit' type='hidden' value='$re[id]'>					<strong>Группа:</strong>					</td>					<td align='left'>					<select class='cforms_sel'  name='user_sel_group_edit' id='user_sel_group_edit' >";                                        $sql = "SELECT * FROM $this->db_group_users";                                        $qw=self::$DB->query($sql);                                        //$i_count = self::$DB->num_rows($qw);                                        while($re = self::$DB->fetch_array($qw)){                                            $sel = '';                                            if($re['id_group'] == $id_user_gr) $sel = 'selected';                                            $text_user_view .="<option $sel value='{$re['id_group']}'>{$re['name_group']}$i_count</option>";                                        }										$text_user_view .= "</select>					</td>				</tr>                                <tr>					<td colspan='2' align='center'>					</td>				</tr>				<tr>					<td colspan='2' align='center'>										</td>				</tr>";				return $text_user_view;	}								function NewUsersView($name,$phone,$pass,$mail,$role,$fio,$group){			$name = self::$DB->escape($name);			$pass = self::$DB->escape($pass);			$mail = self::$DB->escape($mail);			$role = self::$DB->escape($role);			$fio = self::$DB->escape($fio);                        $group = self::$DB->escape($group);			$upass = $pass;			$pass = md5(md5($pass));			$sql = "SELECT name FROM $this->db_users_table WHERE name='$name' ";			$qw=self::$DB->query($sql);			if (self::$DB->num_rows($qw)>0) {				return '-2';			}						$dat_reg = date('Y-m-d H:i:s');			$sql = "INSERT INTO $this->db_users_table (name,fio,phone,pass,upass,mail,dat_reg,role,id_group) 							VALUES ('$name','$fio','$phone','$pass','$upass','$mail','$dat_reg','$role','$group')";			$qw=self::$DB->query($sql);			$this->adminMailReg($fio,$mail,$name,$upass);			if($sql==true){					return 1;				}else{					return '-1';			}	}	function AddNewsBoard($text){			$text = self::$DB->escape($text);			$dat = date('Y-m-d H:i:s');			$sql = "INSERT INTO $this->db_board_table (text) VALUES ('$text')";			$qw=self::$DB->query($sql);			if($sql==true){				return 1;				}else{					return '-1';			}		}		function NewUsersUpd($name,$fio,$phone,$pass,$mail,$role,$id,$group){                        $chek_pass = '';			$name = self::$DB->escape($name);			$pass = self::$DB->escape($pass);			$mail = self::$DB->escape($mail);			$role = self::$DB->escape($role);			$id = self::$DB->escape($id);			$fio = self::$DB->escape($fio);			$phone = self::$DB->escape($phone);                        $group = self::$DB->escape($group);			if($pass!=''){                                $upass = $pass;                                $pass = md5(md5($pass));                                $chek_pass = ",pass='$pass',upass='$upass'";			}			$sql = "UPDATE $this->db_users_table 				SET name='$name',fio='$fio',phone='$phone',mail='$mail',role='$role',id_group='$group' $chek_pass				WHERE id ='$id' ";                                			$qw=self::$DB->query($sql);			if($sql==true){					return 1;				}else{					return '-1';			}	}		function NameUsersView($id){			$arr_us = array();			$id = self::$DB->escape($id);			$sql = "SELECT name FROM $this->db_users_table WHERE id='$id' LIMIT 1 ";			$qw=self::$DB->query($sql);			if (self::$DB->num_rows($qw)>0) {				$re = self::$DB->fetch_array($qw);				$arr_us[$id] .=$re['name'];				return $arr_us;			}else{				return 'Error Users';			}					}					function UsersDelete($users_id){			$sql = "DELETE FROM $this->db_users_table WHERE id IN ($users_id) ";			$qw=self::$DB->query($sql);			if($sql==true){				return 1;				}else{					return '-1';			}		}		function NewsDelete($id){			$sql = "DELETE FROM $this->db_board_table WHERE id = '$id' ";			$qw=self::$DB->query($sql);			if($sql==true){				return 1;				}else{					return '-1';			}		}		function EditNews($id_news,$text_news_admin){			$sql = "UPDATE $this->db_board_table SET text='$text_news_admin' WHERE  id = '$id_news' ";			$qw=self::$DB->query($sql);			if($sql==true){				return 1;				}else{					return '-1';			}		}			function ViewNewsText($id){				$sql = "SELECT id,text FROM $this->db_board_table WHERE id='$id' ORDER BY id DESC ";				$qw=self::$DB->query($sql);					if (self::$DB->num_rows($qw)>0) {					$re = self::$DB->fetch_array($qw);							$text_user_view = "								<tr>								<script src='ckeditor/ckeditor.js' type='text/javascript'></script>									<td align='left'>									<textarea name='news_text_edit' id='news_text_edit'>$re[text]</textarea>	                                <script>	                                    CKEDITOR.replace( 'news_text_edit' );	                                </script>									<input name='news_id_edit' id='news_id_edit' type='hidden' value='$re[id]'>										</td>								</tr>";					}								return $text_user_view;		}		function ViewAddTextNews(){						$text_user_view = "								<tr>								<script src='ckeditor/ckeditor.js' type='text/javascript'></script>									<td align='left'>									<textarea name='news_add_edit' id='news_add_edit'>$re[text]</textarea>	                                <script>	                                    CKEDITOR.replace( 'news_add_edit' );	                                </script>									<input name='news_id_edit' id='news_id_edit' type='hidden' value='$re[id]'>										</td>								</tr>";													return $text_user_view;		}	function NewsBoardUpdate($text,$id){			$text = self::$DB->escape($text);			$id = self::$DB->escape($id);			$sql = "UPDATE $this->db_board_table SET text='$text' WHERE id ='$id' ";			$qw=self::$DB->query($sql);			if($sql==true){					return 1;				}else{					return '-1';			}	}		function ViewNewsBoard(){			$sql = "SELECT id,text FROM $this->db_board_table ORDER BY id DESC";			$qw=self::$DB->query($sql);				if (self::$DB->num_rows($qw)>0) {					$i_count = self::$DB->num_rows($qw);					while($re = self::$DB->fetch_array($qw)){						echo"							<tr class= 'tablnews_view_tr'>							<td valign='top' align='center' width='35'>							<a href='#' onClick='edit_news($re[id]);'><img src='images/edit.png' /></a> 							<a href='#' onClick='del_news($re[id]);'><img src='images/cross.png' /></a>												</td>							<td align='left' >$re[text]</td>							</tr>";						$i_count= $i_count - 1;						}				}								return $text_news_view;		}		function OnlineUser(){			$sql = "SELECT id FROM $this->db_users_table WHERE ses!='0'&&role!='1' ";			$qw=self::$DB->query($sql);			$i_count = self::$DB->num_rows($qw);			return $i_count;		}	function OutMail($name_out,$mail_out,$quest_out){			$mail_site = "info@teletrade.tv";			$subject = 'Сообщение сайта';// тема письма			$subject = "=?utf-8?b?" .base64_encode($subject). "?="; 			$headers  = 'MIME-Version: 1.0' . "\r\n";			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";			$headers .= "From: Site Invest Club <info@teletrade.tv>\r\n";				$message = "			<html>			<head>			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />			</head>			<body>			<hr>			Имя: $name_out			<br>			е-mail: $mail_out			<br />			Вопрос: $quest_out			<br />			<hr>						<body>			</html>			";		return mail($mail_site, $subject, $message, $headers);	}	function OutMailreg($name_out,$mail_out,$reg,$login,$pass){			$mail_site = "info@teletrade.tv";			$subject = 'Регистрация в Invest Club TeleTrade TV';// тема письма			$subject = "=?utf-8?b?" .base64_encode($subject). "?="; 			$headers  = 'MIME-Version: 1.0' . "\r\n";			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";			$headers .= "From: Invest Club TeleTrade TV <info@teletrade.tv>\r\n";			if($reg == 1){			$text = "Ваша регистрация в Invest Club TeleTrade TV подтверждена.<br>						Ваши данные входа:<br>						Логин: $login<br>						Пароль: $pass";				}else{				$text = "Ваша регистрация в Invest Club TeleTrade TV не подтверждена.";			}	$message = "			<html>			<head>			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />			</head>			<body>			<hr>			Здравствуйте $name_out			<br>			$text			<br>			Если Вы не регистрировались, сообщите нам на e-mail: info@teletrade.tv			<br>			<hr>						<body>			</html>			";		return mail($mail_out, $subject, $message, $headers);	}	function adminMailReg($fio,$mail,$login,$pass){			$subject = 'Регистрация в Invest Club';// тема письма			$subject = "=?utf-8?b?" .base64_encode($subject). "?="; 			$headers  = 'MIME-Version: 1.0' . "\r\n";			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";			$headers .= "From: Site Invest Club <info@teletrade.tv>\r\n";				$message = "			<html>			<head>			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />			</head>			<body>			<hr>			Здравствуйте $fio			<br>			Вы прошли регистрацию в Invest Club TeleTrade TV.			<br>			Ваши данные входа:			<br>			Логин: $login			<br>			Пароль: $pass			<br>			Ваша регистрация должна быть подтверждена Администрацией  Invest Club TeleTrade TV.			<br>			Если Вы не регистрировались, сообщите нам на e-mail: info@teletrade.tv			<hr>						<body>			</html>			";			return mail($mail, $subject, $message, $headers);	}}?>