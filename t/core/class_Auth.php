<?php	require 'DB.php';	//$rp_pass = UTF8::substr(md5(time() . $_SERVER['REMOTE_ADDR']), 3, 10);	if(isset($_POST['pass'])){		$hp = $_POST['pass'];		if ($hp != ''){$hp = md5(md5(trim($_POST['pass'])));}	}	if(isset($_POST['login'])){$login = trim($_POST['login']);  if ($login == ''){unset($login);}}	if(isset($_POST['cl_name'])){$cl_name = trim($_POST['cl_name']);  if ($cl_name == ''){unset($cl_name);}}	if(isset($_POST['cl_mail'])){$cl_mail = trim($_POST['cl_mail']);  if ($cl_mail == ''){unset($cl_mail);}}	if(isset($_POST['cl_fio'])){$cl_fio = $_POST['cl_fio'];}	if(isset($_POST['cl_phone'])){$cl_phone = $_POST['cl_phone'];}	if(isset($_POST['cl_pass'])){		$cl_upass = trim($_POST['cl_pass']);		$cl_pass = md5(md5(trim($_POST['cl_pass'])));  		if ($cl_pass == ''){unset($cl_pass);}}	if(isset($_POST['closed_c'])){$closed_c = intval($_POST['closed_c']);} 	if(isset($_POST['out'])){$out = trim($_POST['out']);} 	if(isset($_POST['key'])){$id_news = intval($_POST['key']);} 	if(isset($_POST['users_id'])){$users_id = intval($_POST['users_id']);} 	if(isset($_POST['del'])){$del = intval($_POST['del']);} 	class userAuth {	public $db_user_table = 'users';	public $db_board_table = 'board';	public $db_guest_table = 'guest';	public $db_voice_table = 'voice_chat';	public  static $DB;	public function __construct() {		self::$DB = new DB();		session_start();	}	public function __destruct() {		self::$DB->close();	}	 function Auth($login,$passw,$closed_c) {		 $ses = md5(time());		 $dat_ses = date('Y-m-d H:i:s');		 $login = self::$DB->escape($login);		 $pass = self::$DB->escape($pass);		 $sql_c = "SELECT closed_chat,status_chat FROM $this->db_voice_table WHERE id='1' ";		 $qw_c=self::$DB->query($sql_c);		 $rc = self::$DB->fetch_array($qw_c);		 $status_chat = $rc['status_chat'];					$sql = "SELECT id,name,role,closed,moderation,ses 										FROM $this->db_user_table WHERE name='$login'&&pass='$passw' ";				$qw=self::$DB->query($sql);				if(self::$DB->num_rows($qw)>0) {//user exist					$re = self::$DB->fetch_array($qw);						if($re['role'] ==1||$re['role']==4){//admin							$error = 1;							}else{							if($status_chat!= 0){							//$error .=$error.' = '.$closed_c;									switch ($closed_c) {										case 1://closed конференция											if($re['closed'] == 1){												$error = 1;												}else{												$error = '-97'; //closed = 0											}										break;										case 2://Briffing && password										if($_SESSION['user_chat']=='$login'){											$error = 1;											}else{												if($re['ses'] == 0){													$error = '1';														}else{														$error ='-200';														}											}											break;										case 3://открытая конференция + модерация											if($re['moderation'] == 1){												$error = 1;												}else{												$error = '-99';//moderation = 0											}										break;										default:										  $error = '-98'; //Логин и пароль есть никакая конфа не открыта 									}								}else{									$error = '-2'; //Логин и пароль есть никакая конфа не открыта 								}							}							if($error == 1){									$_SESSION['user_chat'] 		= $login;									$_SESSION['user_role_chat']	= $re['role'];									$_SESSION['new_ses']	= $ses;									$sql = "UPDATE $this->db_user_table SET ses ='$ses',dat_ses='$dat_ses' 												WHERE id='$re[id]' ";									$qw=self::$DB->query($sql);							}else{							$error ='-96';//Neizvestna error												}					}else{ //no user && password					if($status_chat!= 0){					$sql1 = "SELECT id,name,ses,role FROM $this->db_user_table WHERE name='$login' ";					$qw1=self::$DB->query($sql1);						if(self::$DB->num_rows($qw1)>0) {//user exist							$rw = self::$DB->fetch_array($qw1);								if($closed_c == 2){//Briffing									if($_SESSION['user_chat']=='$login'){//Povtor logina										$sql = "UPDATE $this->db_user_table SET ses ='$ses',dat_ses='$dat_ses' 										WHERE id='$rw[id]' ";										$qw=self::$DB->query($sql);										$_SESSION['user_chat'] 		= $login;										$_SESSION['user_role_chat'] = $rw['role'];										$_SESSION['new_ses']	= $ses;										$error = '1';									}else{										if($rw['ses'] == 0){										$sql = "UPDATE $this->db_user_table SET ses ='$ses',dat_ses='$dat_ses' 										WHERE id='$rw[id]' ";										$qw=self::$DB->query($sql);										$_SESSION['user_chat'] 		= $login;										$_SESSION['user_role_chat'] = $rw['role'];										$_SESSION['new_ses']	= $ses;										$error = '1';										}else{										$error ='-200';											}																		}								}else{									$error = '-102';//conference not briffing								}								//имя может быть уже зарегено в базе и тогда пропускаем + обновляем ses								//если есть имя но нет пароля значит либо идет сессия  ses !=0								// либо сессия закрыта 0 и осталось имя ses =0						}else{//if don't name								if($closed_c == 2){//Briffing									//NEW USER ROLE = 3									$sql_ins = "INSERT INTO $this->db_user_table (name,ses,dat_ses,role) 									VALUES ('$login','$ses','$dat_ses','3')";									$qw_ins=self::$DB->query($sql_ins);									$_SESSION['user_chat'] 		= $login;									$_SESSION['user_role_chat']	= 3; //guest user									$_SESSION['new_ses']	= $ses;									$error = '1';									}else{									$error = '-100';								}						}					}else{						$error = '-1';					}				}					if($error == 1){						$_SESSION['user_chat'] 		= $login;						$_SESSION['new_ses']	= $ses;					}					return $error;	}		function AuthRegistrtion($cl_name,$cl_phone,$cl_pass,$cl_mail,$cl_role,$cl_fio,$cl_upass){			$cl_name = self::$DB->escape($cl_name);			$cl_pass = self::$DB->escape($cl_pass);			$cl_mail = self::$DB->escape($cl_mail);			$cl_fio = self::$DB->escape($cl_fio);			$cl_upass = self::$DB->escape($cl_upass);			$cl_phone = self::$DB->escape($cl_phone);			$u_ipgeo = self::$DB->escape($_SESSION['user_geo_ip']);			$u_citygeo = self::$DB->escape($_SESSION['user_geo_city']);			$u_coutrygeo = self::$DB->escape($_SESSION['user_geo_country']);			$u_regiongeo = self::$DB->escape($_SESSION['user_geo_region']);			$sql = "SELECT name FROM $this->db_user_table WHERE name='$cl_name' ";			$qw=self::$DB->query($sql);			if (self::$DB->num_rows($qw)>0) {				return '-2';			}						$dat_reg = date('Y-m-d H:i:s');				$sql = "INSERT INTO $this->db_user_table (name,fio,phone,pass,upass,mail,dat_reg,role,ip_reg,city_reg,region_reg,country_reg) 							VALUES ('$cl_name','$cl_fio','$cl_phone','$cl_pass','$cl_upass','$cl_mail','$dat_reg','$cl_role',							'$u_ipgeo','$u_citygeo','$u_regiongeo','$u_coutrygeo')";			$qw=self::$DB->query($sql);			if($sql==true){				$this->OutMailRegistration($cl_fio,$cl_mail,$cl_name,$cl_upass);				return 1;				}else{					return '-1';			}	}			function CheckSession($user,$role){				$dat_ses = date('Y-m-d H:i:s');				$sql = "SELECT ses FROM $this->db_user_table WHERE name='$user' ";				$qw=self::$DB->query($sql);				if (self::$DB->num_rows($qw)>0) {					$re = self::$DB->fetch_array($qw);					$ses = $re['ses'];				}else{					return '-1';				}				if($role==1||$role==4){					return 1;				}				if($role==2||$role==3){					if($re['ses']!=0){						return	1;						}else{							if($_SESSION['new_ses']){										$ses = $_SESSION['new_ses'];										$sql = "UPDATE $this->db_user_table SET ses ='$ses',dat_ses='$dat_ses'															WHERE id='$id' ";										$qw=self::$DB->query($sql);										return	1;										}else{												return	'-100';															}					}								}					return '-101';		}					function DelSession($user){			$sql = "UPDATE $this->db_user_table SET ses ='0' WHERE name='$user'  ";			$qw=self::$DB->query($sql);				unset($_SESSION['user_chat']);				unset($_SESSION['user_role_chat']);			if($sql==true){					return 1;				}else{					return '-1';			}	}										function Auth_out() {		//for admins		$adm_name = str_replace("_Admin", "", $_SESSION['user_chat']);		$sql = "UPDATE $this->db_user_table SET ses ='0' WHERE name='$adm_name'  ";		$qw=self::$DB->query($sql);		unset($_SESSION['user_chat']);		unset($_SESSION['user_role_chat']);	}	function OutMailRegistration($fio,$mail,$login,$pass){			$subject = 'Регистрация в Invest Club';// тема письма			$subject = "=?utf-8?b?" .base64_encode($subject). "?="; 			$headers  = 'MIME-Version: 1.0' . "\r\n";			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";			$headers .= "From: Site Invest Club <info@teletrade.tv>\r\n";				$message = "			<html>			<head>			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />			</head>			<body>			<hr>			Здравствуйте $fio			<br>			Вы прошли регистрацию в Invest Club.			<br />			Ваши данные входа:			<br />			Логин: $login			<br />			Пароль: $pass			<br />			Если Вы не регистрировались, сообщите нам на e-mail: info@teletrade.tv			<hr>						<body>			</html>			";		return mail($mail, $subject, $message, $headers);	}	function ip_users_geo($arr_geo,$ip_user,$uname) {		 $geocity		= $arr_geo['city'];		 $geocountry	= $arr_geo['country'];		 $georegion 	= $arr_geo['region_name'];		 $current_date	= date('Y-m-d H:i:s');				$sql = "UPDATE $this->db_user_table 									SET 									ip 		='$ip_user',									city 	='$geocity',									region	='$georegion',									country	='$geocountry',									dat_ses ='$current_date'									WHERE name='$uname' ";					$qw=self::$DB->query($sql);	}}//End class Auth	$auth = new userAuth();		if(isset($login)&&isset($hp)){		echo $auth->Auth($login,$hp,$closed_c);	}	if(isset($cl_name)&&isset($cl_pass)&&isset($cl_mail)){		echo $auth->AuthRegistrtion($cl_name,$cl_phone,$cl_pass,$cl_mail,'2',$cl_fio,$cl_upass);	}		if(isset($out)){		echo $auth->Auth_out();	}unset($auth);?>