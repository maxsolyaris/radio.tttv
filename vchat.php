<?php
session_start();
//header('Content-Type: text/html; charset=utf-8');
//require_once 'autchk.php';
require_once 'core/class_Check.php';
require_once 'core/class_News.php';

if (isset($_POST['check'])) {
    $check = intval($_POST['check']);
}
if (isset($_POST['name'])) {
    $name = trim($_POST['name']);
}
if (isset($_POST['mail'])) {
    $mail = trim($_POST['mail']);
}
if (isset($_POST['fio'])) {
    $fio = $_POST['fio'];
}
if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
}


if (isset($_POST['pass'])) {
    $pass = trim($_POST['pass']);
    if ($pass == '') {
        $pass = time();
    }
}
if (isset($_POST['newpass'])) {
    $newpass = trim($_POST['newpass']);
}
if (isset($_POST['role'])) {
    $role = $_POST['role'];
}
if (isset($_POST['text'])) {
    $text = $_POST['text'];
}
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}
if (isset($_POST['close_chat'])) {
    $close_chat = intval($_POST['close_chat']);
}
if (isset($_POST['moder_chat'])) {
    $moder_chat = intval($_POST['moder_chat']);
}
if (isset($_POST['name_out'])) {
    $name_out = trim($_POST['name_out']);
}
if (isset($_POST['mail_out'])) {
    $mail_out = trim($_POST['mail_out']);
}
if (isset($_POST['quest_out'])) {
    $quest_out = trim($_POST['quest_out']);
}
if (isset($_POST['user'])) {
    $username = trim($_POST['user']);
}
$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid
if (isset($_GET['sidx'])) {
    $sidx = $_GET['sidx'];
} else {
    $sidx = 'dat_reg';
}
if (isset($_GET['sord'])) {
    $sord = $_GET['sord'];
} else {
    $sord = 'desc';
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if (isset($_GET['check'])) {
    $check = intval($_GET['check']);
}
if (isset($_GET['name'])) {
    $name = trim($_GET['name']);
}
if (isset($_GET['mail'])) {
    $mail = trim($_GET['mail']);
}
if (isset($_GET['fio'])) {
    $fio = trim($_GET['fio']);
}
if (isset($_GET['phone'])) {
    $phone = trim($_GET['phone']);
}
if (isset($_GET['newpass'])) {
    $newpass = trim($_GET['newpass']);
}
if (isset($_GET['role'])) {
    $role = intval($_GET['role']);
}
if (isset($_GET['group'])) {
    $group = intval($_GET['group']);
}
if (isset($_GET['action_group'])) {
    $action_group = intval($_GET['action_group']);
}
if (isset($_GET['group_name'])) {
    $group_name = trim($_GET['group_name']);
}
if (isset($_GET['close_chat'])) {
    $close_chat = intval($_GET['close_chat']);
}
if (isset($_GET['moder_chat'])) {
    $moder_chat = intval($_GET['moder_chat']);
}

if (isset($_GET['group_flag'])) {
    $group_flag = intval($_GET['group_flag']);
    }
if (isset($_GET['type_reg'])) {
    $type_reg = intval($_GET['type_reg']);
    }
if (isset($_GET['pass'])) {
    $pass = trim($_GET['pass']);
    if ($pass == '') {
        $pass = time();
    }
}
$flg_edit = (isset($_GET['flg_edit'])) ? intval($_GET['flg_edit']) : false;

$chkview = new CheckView();
$v_user = new BoardView();

switch ($check) {
    case 1:
        $chkview->UsersConfereceCheck($id, $close_chat);
        echo $v_user->ViewUsers_jq($sidx, $sord, $page, $limit);
        break;
    case 2:
        if ($id) {
            echo $chkview->EditViewUsers($id);
        }
        break;
    case 3://Board users view 
        if ($pass) {
            $chkview->NewUsersView($name, $phone, $pass, $mail, $role, $fio, $group);
        }
        echo $v_user->ViewUsers_jq($sidx, $sord, $page, $limit);
        break;
    case 4://Board users delete
        $chkview->UsersDelete($id);
        //echo $v_user->ViewUsers_jq($sidx,$sord,$page,$limit);
        break;
    case 5://Board users edit
        $chkview->NewUsersUpd($name, $fio, $phone, $newpass, $mail, $role, $id, $group);
        echo $v_user->ViewUsers_jq($sidx, $sord, $page, $limit);
        break;
    case 6:
        if ($id) {
            $arr_del = $chkview->NameUsersView($id);
            echo "$arr_del[$id] <input name='id_del' id='id_del' type='hidden' value='$id'>";
        }
        break;
    case 7:
        if ($id) {
            echo $chkview->ViewNewsText($id);
        }
        break;
    case 8://Board news view 
        //echo "$text,$id";
        $chkview->NewsBoardUpdate($text, $id);
        echo $chkview->ViewNewsBoard();
        break;
    case 9://Board news add 
        $chkview->AddNewsBoard($text);
        echo $chkview->ViewNewsBoard();
        break;
    case 10://Board news view 
        $news_add_ = $chkview->NewsBoardUpdate($text, $id);
        if ($news_add_ = 1) {
            echo $chkview->ViewNewsBoard();
        }
        break;
    case 11://Board news view 
        if ($id) {
            $chkview->NewsDelete($id);
        }
        echo $chkview->ViewNewsBoard();
        break;
    case 12://DELETE user 
        if ($id) {
            $chkview->UsersDelete($id);
        }
        echo $v_user->ViewUsers_jq($sidx, $sord, $page, $limit);
        break;
    case 13:
        echo $chkview->ViewAddTextNews();
        break;
    case 14:
        $chkview->OutMail($name_out, $mail_out, $quest_out);
        break;
    case 15:
        $v_user->UserClosed($username);
        //echo $username;
        break;
    case 16:
        echo "Количество пользователей on-Line: " . $chkview->OnlineUser();
        break;
    case 17:
        $chkview->UsersConfereceModeration($id, $moder_chat);
        echo $v_user->ViewUsers_jq($sidx, $sord, $page, $limit);
        break;
    case 18:
        echo $v_user->ViewUsers_jq($sidx, $sord, $page, $limit, $group_flag, $group);
		//echo "($sidx, $sord, $page, $limit, $group_flag, $group)";
        break;
    case 19:
        echo $v_user->regUsersCount($type_reg, $group_flag);
        break;
    case 20:
        echo $v_user->addUsersInGroup($group, $id);
        break;
    case 200:
        echo $v_user->editGroupUsers($group, $group_name, $action_group);
        break;
    case 201:
        echo $v_user->addGroupUsers($group_name);
        break;
    case 202:
        echo $v_user->delGroupUsers($group);
        break;
    case 203:
        echo $v_user->ViewSelectGroup($flg_edit);
        break;
}
unset($v_user);
unset($chkview);
?>	
