<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");
	$id = -1;
	
	if($user_read = $query_user->fetch_row()) {
		echo $id;
	} else {
		$mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('".$login."', '".$password."', 0)");

		$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];
			
		if($id != -1) {
            $_SESSION['user'] = $id;
            
            $Ip = $_SERVER['REMOTE_ADDR'];
            $DateStart = date("Y-m-d H:i:s");

            $SqlSession = "INSERT INTO `session`(`IdUser`, `Ip`, `DateStart`, `DateNow`) VALUES ({$id}, '{$Ip}', '{$DateStart}', '{$DateStart}')";
            $mysqli->query($SqlSession);

            $SqlGetId = "SELECT `Id` FROM `session` WHERE `DateStart` = '{$DateStart}' AND `IdUser` = {$id};";
            $QueryId = $mysqli->query($SqlGetId);
            $ReadId = $QueryId->fetch_assoc();
            $_SESSION["IdSession"] = $ReadId['Id'];

            $SqlLog = "INSERT INTO `logs` (`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) ".
                      "VALUES ('{$Ip}', {$id}, '{$DateStart}', '00:00:00', 'Новый пользователь {$login} зарегистрировался в системе.')";
            $mysqli->query($SqlLog);
        }
		echo $id;
	}
?>