<?php
/* кодировка файлов utf8 */
session_start();
require_once 'core/class_Auth.php';
require_once 'core/class_News.php';
include("core/SxGeo.php");
include("core/ip_geo_user.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Radio.Teletrade.TV</title>
        <meta name="keywords" content="radio.teletrade.tv" />
        <meta name="description" content="radio.teletrade.tv" />
        <link type="text/css" href="css/jquery-ui-1.8.19.custom.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="css/ui.jqgrid.css" type="text/css" media="screen, projection" />
        <link href="css/tutorsty.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
        <script type="text/javascript" src="ckeditor/ckeditor.js" ></script>
        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="js/swfobject.js"></script>
        <script type="text/javascript" src="js/swfformfix.js"></script>
        <script type='text/javascript' src="js/i18n/grid.locale-ru.js"></script>
        <script type='text/javascript' src="js/jquery.jqgrid.min.js"></script>
        <script type='text/javascript' src="js/jquery.tablednd.js"></script>
        <script type='text/javascript' src="js/tablesorter.js"></script>
        <script type="text/javascript">
            function getSWF(movieName) {
                //alert("browser "+navigator.appName);
                if (navigator.appName.indexOf("Microsoft") != -1) {
                    return window[movieName];
                    //return document.getElementById(movieName);
                } else {
                    return document[movieName];
                }
            }
            function loginUser() {
                var uName = "user_" + Math.random();
                getSWF("MayakovskiyChat").loginUser(uName);
            }

            // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
            var swfVersionStr = "11.1.0";
            // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
            var xiSwfUrlStr = "playerProductInstall.swf";
            var flashvars = {<?php echo $chat_flash_vars; ?>};
            flashvars.ip = "<?php echo $_SESSION['user_geo_ip']; ?>";
            var params = {};
            params.quality = "high";
            params.bgcolor = "#ffffff";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
            var attributes = {};
            attributes.id = "<?php echo $chat_flash_id; ?>";
            attributes.name = "<?php echo $chat_flash_name; ?>";
            attributes.align = "middle";
            swfobject.embedSWF(
                    "<?php echo $chat_flash_swf; ?>", "flashContent",
                    "350", "600",
                    swfVersionStr, xiSwfUrlStr,
                    flashvars, params, {wmode: "opaque"});
            // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
            swfobject.createCSS("#flashContent", "display:block;text-align:left;margin:0");
        </script>

        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-52542331-1', 'teletrade.tv');
            ga('require', 'displayfeatures');
            ga('send', 'pageview');
        </script>

    </head>
    <body>

        <div id="main">
            <!-- <div id="header">
                            header в случае надобности
                </div> -->
            <!-- #header-->
            <div class="wrapper">
                <div class="left_block">
                    <img class="img_logo" src="images/logo_invest.jpg"  />
                    <div id="inform">
                        <?php
                        //echo "view_chat=$view_chat view_user = $view_user<br>";
                        $usernews_conf = $news->ConfUsersChat();
                        if ($view_user == 1 || $view_user == 4 || $view_user == 41 || $view_user == 11) {
                            echo '<a class="right_reg" id="out_client_chat" href="#">Выход</a>  
						 <a class="right_reg" onclick="UserManagerView(); return false;" href="#">on-Line</a>';
                        } else {
                            echo '<a class="right_reg" href="http://www.teletrade.tv"> Перейти на TeleTrade TV</a>';
                        }
                        echo "<span class='right_reg'>$usernews_conf</span>";
                        ?>
                        <div class="img_but_client">
                            <a href="https://siteheart.com/webconsultation/498953?" target="siteheart_sitewindow_498953" onclick="o = window.open;
                                    o('https://siteheart.com/webconsultation/498953?', 'siteheart_sitewindow_498953', 'width=550,height=400,top=30,left=30,resizable=yes');
                                    return false;"><img src="http://webindicator.siteheart.com/webindicator/image/1380129329?ent=498953&company=498953" border="0" alt="SiteHeart" /></a> 
                            <a id="mail_out_us" href="#"><img src="images/mail.jpg"/></a>
                            <input name="username" id="username" type="hidden" value="<?php echo $_SESSION['user_chat']; ?>">
                        </div>
                    </div>
                    <?php
                    if ($_SESSION['user_chat'] && $_SESSION['user_chat'] != '') {
                        $class_lg = 'no_but_auth_log';
                    } else {
                        $class_lg = 'but_auth_log';
                    }
                    if ($view_user == 1 || $view_user == 4) {
                        echo'
							<div id="flashContent">
							<p>
								To view this page ensure that Adobe Flash Player version 
								11.1.0 or greater is installed. 
							</p>
							<script type="text/javascript" src="flash_chat.js"></script>
							</div>';
                    } else {
                        echo"
							<div id='flashContent'>
							<p>
								To view this page ensure that Adobe Flash Player version 
								11.1.0 or greater is installed. 
							</p>
							<script type='text/javascript' src='flash_chat.js'></script>
							</div>
								<a href='#' class='$class_lg' id='opener'>
							<img src='images/login_but.png'/></a>";
                    }
                    ?>
                </div>
                <div class="right_block">
                    <!--Start ROBOT "Анонсы TeleTrade TV" tags-->
                    <!--При копировании для корректного отображения робота свяжитесь с нами по e-mail info@teletrade.tv -->
                    <script type="text/javascript" src="http://teletrade.tv/robot/js/tmpl=image/get=anons_robot/width=640/height=225/color_top=ffffff/color_border=ffffff/color_bg=ffffff/color_bg_top=ffffff/color_bottom=ffffff"></script>
                    <!--Свободная высота для рисунка = height минус 111-->
                    <!--End ROBOT "Анонсы TeleTrade TV" tags -->
                    <div id="border_d"></div>
                    <div id="place">
                        <!--Start ROBOT "TeleTrade TV Блок на radio.teletrade.tv" tags -->
                        <!--При копировании для корректного отображения робота свяжитесь с нами по e-mail info@teletrade.tv -->
                        <script type="text/javascript" src="http://teletrade.tv/robot/js/tmpl=embed_block/get=block_radioteletradetv/width=640/height=auto"></script>
                        <!-- End ROBOT "TeleTrade TV Блок на radio.teletrade.tv" tags -->
                        <div class="bulletinboard">
                            <div class="title_news">Доска объявлений 
                                    <?php
                                    if ($view_user == 1) {
                                        echo"	<a href='#' id='add_news_chat_us'><img src='images/reportplus.png' /></a>";
                                    }
                                    ?>
                            </div>
                            <div class="container">
                                <div id="content">
                                    <?php
                                    $strNews = $news->ViewNews();
                                    $i = count($strNews);
                                    if ($view_user == 1) {
                                        echo '<table id="table_news" width="621" border="0"  cellspacing="0">';
                                        foreach ($strNews as $key => $str) {
                                            echo"
												<tr class= 'tablnews_view_tr'>
												<td valign='top' align='center' width='35'>
												<a href='#' onClick='edit_news($key);'><img src='images/edit.png' /></a>  <a href='#' onClick='del_news($key);'><img src='images/cross.png' /></a>
												</td>
												<td align='left' >$str</td>
												</tr>";
                                            $i = $i - 1;
                                        }
                                        echo "</table>";
                                    } else {
                                        echo '<table width="621" border="0"  cellspacing="0">';
                                        foreach ($strNews as $key => $str) {
                                            echo"
												<tr class= 'tablnews_view_tr'>
												<td align='left' >$str</td>
												</tr>";
                                            $i = $i - 1;
                                        }
                                        echo "</table>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- End right_block -->	
                </div>
                <div class="cl"></div>
                <?php
                if ($view_user == 1) {
                    $reg_users_conf = $news->regUsersCount(1);
                    //$reg_moder_conf = $news->regUsersCount(2);
                    $view_activ_group = $news->activGroupUsers();
                    echo"<div class='bulletinboard'>
                	<div class='title_us'>Пользователи
                        	<select class='mssort1' id='regmoder_users' onchange='f_UsersOptions(this.value);'>
                                	<option value=''>Выберите операцию...</option>
					<option value='100'>Установить закрытую конференцию</option>
					<option value='101'>Отменить закрытую конференцию</option>
					<option value='102'>Подтверждение регистрации</option>
					<option value='103'>Отменить подтверждение регистрации</option>
					<option value='104'>Добавить пользователя</option>
					<option value='105'>Удалить пользователя</option>
                                        <option value='106'>Выбрать группу для пользователя</option>
                                        <option value='107'>Отобразить всех пользователей</option>
				</select> 
				Группы
				<select class='mssort1' id='reg_group_users' onchange='f_UsersOptions(this.value);'>
					<option value=''>Выберите операцию...</option>
					<option value='200'>Редактировать группу</option>
					<option value='201'>Добавить группу</option>
					<option value='202'>Удалить группу</option>
				</select>
                            <div class='right_id'>
                                    Акт.группа: <a id='view_activ_group' href='#'>$view_activ_group</a>; 
                                    Зарег.: <span id='reg_users_conf'>$reg_users_conf</span>; Неподтв.: <span id='reg_moder_conf'>$reg_moder_conf</span>
                                    <a title='Отчет о пользователях'  href='report.php' id='user_manager_conf'>
                                    <img src='images/xls_i.png' /></a>
                            </div>
							<input name='sel_gr_section' id='sel_gr_section' type='hidden' value=''>
        		</div>
				
			<table id='list3'></table>
			<div id='pager3'></div>
                </div>";
                }
                ?>
            </div> <!-- End wrapper -->
            <div class="cl"></div>
            <!-- <div id="footer">
                    </div> -->
            <!-- #footer -->

        </div><!-- #main -->
        <div id="dialog" >
            <div id="view_auth_client">
                <table id="view_auth_table" width='320' border='0' cellspacing='5' cellpadding='5'>
                </table>
            </div>
        </div>

        <div id="dialog_reg" title="Регистрация пользователя" >
            <table width="400" border="0" cellspacing="5" cellpadding="5">
                <tr>
                    <td width="120px" align="right">
                        <strong>Введите логин:</strong><span style="color: red">*</span>
                    </td>
                    <td  align="left">
                        <input class="cforms_reg" type="text" name="client_name" id="client_name"  />
                    </td>
                </tr>
                <tr>
                    <td width="120px" align="right">
                        <strong>Введите имя:</strong><span style="color: red">*</span>
                    </td>
                    <td  align="left">
                        <input class="cforms_reg_val" type="text" name="client_fio" id="client_fio" value="Введите Ваше ФИО"/>
                    </td>
                </tr>
                <tr>
                    <td width="120px" align="right">
                        <strong>Введите телефон:</strong><span style="color: red">*</span>
                    </td>
                    <td  align="left">
                        <input class="cforms_reg" type="text" name="client_phone" id="client_phone"/>
                    </td>
                </tr>

                <tr>
                    <td align="right">
                        <strong>Введите e-mail:</strong><span style="color: red">*</span>
                    </td>
                    <td align="left">
                        <input class="cforms_reg"  type="text" name="client_mail" id="client_mail"  />
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <strong>Введите пароль:</strong><span style="color: red">*</span>
                    </td>
                    <td align="left">
                        <input class="cforms_reg"  type="text" name="client_pass" id="client_pass"  />
                    </td>
                </tr>

                <tr>
                    <td colspan="2" align="center">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button id="work_reg"> Зарегистрироваться </button> </br>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        if ($view_user == 1) {
            $view_group = $news->ViewSelectGroup();
            echo "<div id='dialog_add_users' title='Добавление нового пользователя'>
	<table width='580' border='0' cellspacing='5' cellpadding='5'>
				<tr>
					<td width='220' align='right'>
					<strong>Введите логин:</strong><span style='color: red'>*</span>
					</td>
					<td  align='left'>
					<input class='cforms_reg' type='text' name='name_add' id='name_add'  />
					</td>
				</tr>
				<tr>
					<td width='120px' align='right'>
					<strong>Введите ФИО:</strong><span style='color: red'>*</span>
					</td>
					<td  align='left'>
					<input class='cforms_reg' type='text' name='fio_add' id='fio_add'/>
					</td>
				</tr>
				<tr>
					<td width='120px' align='right'>
					<strong>Введите телефон:</strong><span style='color: red'>*</span>
					</td>
					<td  align='left'>
					<input class='cforms_reg' type='text' name='phone_add' id='phone_add'/>
					</td>
				</tr>
				<tr>
					<td align='right'>
					<strong>Введите e-mail:</strong><span style='color: red'>*</span>
					</td>
					<td align='left'>
					<input class='cforms_reg'  type='text' name='mail_add' id='mail_add'/>
					</td>
				</tr>
				<tr>
					<td align='right'>
					<strong>Введите пароль:</strong><span style='color: red'>*</span>
					</td>
					<td align='left'>
					<input class='cforms_reg'  type='text' name='pass_add' id='pass_add'/>
					</td>
				</tr>
				<tr>
					<td align='right'>
					<strong>Выберите тип пользователя:</strong><span style='color: red'>*</span>
					</td>
					<td align='left'>
					<select class='cforms_sel'  name='sel_role_add' id='sel_role_add'>
					<option value='2'>Пользователь</option>
					<option value='4'>Speaker</option>
					<option value='1'>Администратор</option>
					</select>
					</td>
				</tr>
				<tr>
					<td align='right'>
					<strong>Группа пользователя:</strong><span style='color: red'>*</span>
					</td>
					<td align='left'>
                                            <select class='cforms_sel'  name='sel_addgr_user' id='sel_addgr_user'>                                            
                                            </select>
                                        </td>
				</tr>
				<tr>
					<td colspan='2' align='center'>
					</td>
				</tr>
				<tr>
					<td colspan='2' align='center'>
					<button id='work_add'> Добавить пользователя </button> </br>
					<input name='w_reg_user' id='w_reg_user' type='hidden' value='$view_user'>
					</td>
				</tr>
	</table>
</div>

<div id='dialog_edit_user'>
<table id='table_user_view' width='400' border='0' cellspacing='5' cellpadding='5'>
</table>
		<div align='center'>
			<button id='work_edit'>Изменить данные</button>
                        </br>
		</div>
</div>

<div id='dialog_del_user'>
<br>
Удалить пользователя <span id='name_user_del'></span>?<br><br>
<button id='work_del'>Удалить</button><button id='canc_del'>Отменить</button>
</div>

<div id='dialog_edit_news'>
	<div id='inform_view_news'></div>
		<br>
	<div align='center'>
		<button id='users_news_edit'>Изменить</button>
			<button id='canc_news'>Закрыть</button>
		</div>	
	</div>
		<div id='dialog_add_news' title='Добавление новой новости'>
			<div id='inform_add_news'></div>
			<tr>
				<td align='center'>
					<br><br>
					<div align='center'>
					<button id='boardnews_add'> Добавить новость </button>
					<button id='canc_news_add'>Отменить</button>
					</div>	
				</td>
				</tr>
</div>	
<div id='dialog_add_group' title='Добавление новой группы'>
			<table width='400' border='0' cellspacing='5' cellpadding='5'>
				<tr>
					<td width='200' align='right'>
					<strong>Название группы:</strong><span style='color: red'>*</span>
					</td>
					<td  align='left'>
					<input class='cforms_reg' type='text' name='group_add' id='group_add'/>
					</td>
				</tr>
			<tr>
			<td colspan = '2' align='center'>
                            <br><br>
                        <div align='center'>
                            <button id='work_add_group'> Добавить </button>
                            <button id='canc_add_group'> Отменить </button>
			</div>	
			</td>
			</tr>
                        </table>
</div>
<div id='dialog_del_group' title='Удалить группу'>
    <table width='400' border='0' cellspacing='5' cellpadding='5'>
	<tr>
            <td width='200' align='right'>
            <strong>Наименование:</strong><span style='color: red'>*</span>
            </td>
            <td  align='left'>
                <select class='cforms_sel'  name='sel_group_del' id='sel_group_del'></select>
           </td>
	</tr>
	<tr>
	<td colspan = '2' align='center'>
            <br><br>
            <div align='center'>
                <button id='work_del_group'> Удалить </button>
                <button id='canc_del_group'> Отменить </button>
            </div>	
	</td>
	</tr>
    </table>
</div>
<div id='dialog_edit_group' title='Редактировать группу'>
    <table width='400' border='0' cellspacing='5' cellpadding='5'>
	<tr>
            <td width='200' align='right'>
            <strong>Группа:</strong><span style='color: red'>*</span>
            </td>
            <td  align='left'>
                <select class='cforms_sel'  name='sel_group_edit' id='sel_group_edit'></select>
           </td>
	</tr>
       <tr>
            <td width='200' align='right'>
            <strong>Изменить имя: </strong><span style='color: red'>*</span>
            </td>
            <td  align='left'>
                <input class='cforms_reg' type='text' name='group_edit_name' id='group_edit_name'/>
            </td>
	</tr>
          <tr>
            <td width='200' align='right'>
                <input type='checkbox' name='activ_group' id='activ_group'>
            </td>
            <td  align='left'>
                Установить группу активной 
            </td>
	</tr>
	<tr>
	<td colspan = '2' align='center'>
            <br>
            <div align='center'>
                <button id='work_edit_group'> Изменить </button>
                <button id='canc_edit_group'> Отменить </button>
            </div>	
	</td>
	</tr>
    </table>
</div>
<div id='add_users_group' title='Добавить пользователей в группу'>
    <table width='400' border='0' cellspacing='5' cellpadding='5'>
	<tr>
            <td width='200' align='right'>
            <strong>Наименование:</strong><span style='color: red'>*</span>
            </td>
            <td  align='left'>
                <select class='cforms_sel'  name='sel_group_users' id='sel_group_users'></select>
           </td>
	</tr>
	<tr>
	<td colspan = '2' align='center'>
            <br><br>
            <div align='center'>
                <button id='work_us_group'> Добавить </button>
                <button id='canc_us_group'> Отменить </button>
            </div>	
	</td>
	</tr>
    </table>
</div>";
        }
        unset($news);
        ?>
        <div id="dialog_err" title="На данный момент для Вас конференция не открыта." >
            На данный момент для Вас конференция не открыта.</br>
            Свяжитесь с администратором или войдите позже на открытую конференцию.
            <br><br><br>
                        <div align="center" >
                            <button id="canc">Закрыть</button>
                        </div>	
                        </div>
                        <div id="dialog_online">
                    </br>
                    <div id="info_online"></div>
                </br>
                <div align="center" >
                    <button id="canc_online">Закрыть</button>
                </div>	
        </div>
        <div  id="email_tel_out"  >
            <table width="480" border="0" cellspacing="5" cellpadding="5">
                <tr>
                    <td align="left">
                        <strong>Ваше имя :</strong><span style="color: red">*</span>
                    </td>
                    <td width="360" align="left">
                        <input type="text" name="name_out" id="name_out" class="cforms"  />
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <strong>Ваш e-mail:</strong><span style="color: red">*</span>
                    </td>
                    <td align="left">
                        <input type="text" name="mail_out" id="mail_out" class="cforms"  />
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <strong>Текст сообщения:</strong><span style="color: red">*</span>
                    </td>
                    <td align="left">
                        <textarea name="quest_out" id="quest_out" cols="35" rows="5"  ></textarea>
                    </td>
                </tr>
            </table>
            <br>
                <input name="quest_tovar_kod" id="quest_tovar_kod" type="hidden" value="">
                    <button id="mail_work">Отправить сообщение</button>
                    <button id="mail_canc">Закрыть</button>
                    </div>

                    <!-- Yandex.Metrika counter -->
                    <script type="text/javascript">
                                (function (d, w, c) {
                                    (w[c] = w[c] || []).push(function () {
                                        try {
                                            w.yaCounter25474097 = new Ya.Metrika({id: 25474097,
                                                webvisor: true,
                                                clickmap: true,
                                                trackLinks: true,
                                                accurateTrackBounce: true});
                                        } catch (e) {
                                        }
                                    });
                                    var n = d.getElementsByTagName("script")[0],
                                            s = d.createElement("script"),
                                            f = function () {
                                                n.parentNode.insertBefore(s, n);
                                            };

                                    s.type = "text/javascript";
                                    s.async = true;
                                    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
                                    if (w.opera == "[object Opera]") {
                                        d.addEventListener("DOMContentLoaded", f, false);
                                    } else {
                                        f();
                                    }
                                })(document, window, "yandex_metrika_callbacks");
                    </script>
                    <noscript><div><img src="//mc.yandex.ru/watch/25474097" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                    <!-- /Yandex.Metrika counter -->

                    </body>
                    </html>