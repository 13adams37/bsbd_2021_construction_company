<?php
	require_once 'includes/sessions.php';
	if (mySession_start())
	{
		header("location: lk.php");
	}
?>
<?php
	if (isset($_POST['log_in'])) 
	{
		$login = htmlspecialchars( trim($_POST['login']) ); 
		$password = htmlspecialchars( trim($_POST['password']) );
					
		if (!empty($login) && !empty($password))
 		{
 			$sql = 'SELECT acc_id, acc_password FROM usadba_accounts WHERE acc_email = :login';
 			$params = [':login' => $login];
 			$stmt = $db->prepare($sql);
 			$stmt->execute($params);
 			$user = $stmt->fetch(PDO::FETCH_OBJ);		
 			if ($user) 
 			{
 				if (password_verify($password, $user->acc_password))
 				{
					mySession_write($user->acc_id);
					header('Location: lk.php');
 				}
 				else
 					echo "Неверный логин или пароль!"; 
 			}
 		else
 			echo "Пользователь не найден!";
 		}
 	else
 		echo "Неверно задан логин или пароль!"; 
	}
	if (isset($_POST['reg'])) 
	{
		header('Location: reg.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Мари усадьба</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="shortcut icon" href="images/favicon.ico"/>
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic&amp;subset=latin,cyrillic" />
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans:300i&amp;subset=cyrillic" rel="stylesheet" />
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="js/prettyphoto/css/prettyPhoto.css" />
        <script src="js/jquery-2.1.3.min.js"></script>
        <script src="js/prettyphoto/js/jquery.prettyPhoto.js"></script>
        <script src="js/jquery.cycle2.min.js"></script>
        <script src="js/jquery.control.js"></script>
        <script src="js/jquery.form.js"></script>
    </head>
    <body id="home">
        <div class="header">
            <div class="wrap">
                <a class="logo" href="index.php">Мари Усадьба <span>строительная компания</span></a>
                <div class="phone">+7 (880) 055-35-35</div>
            </div>
        </div>
        <div class="nav">
            <div class="wrap">
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="about.php">О фирме</a></li>
                    <li><a href="project.php">Проекты</a></li>
					<li><a href="order.php">Заказы</a></li>
					<?php
						if (mySession_start())
							echo '<li><a href="lk.php">Личный кабинет</a></li>';
						else
							echo '<li><a href="login.php">Войти</a></li>';
					?>
                </ul>
            </div>
        </div>
	<div class="auth" id="login">
		<form action="" method="post">
 			Логин: <input type="text" name="login" />
 			Пароль: <input type="password" name="password" />
 			<input class="button-green" type="submit" value="Войти" name="log_in" />
			<input class="button-red" type="submit" value="Регистрация" name="reg" />
		</form>
	</div>
    <div class="footer">
        <div class="wrap">
            <div class="copy">2021 &copy; <a href="index.php">Мари Усадьба</a><br />Деревянное домостроение по всей России</div>
        </div>		
    </div>
</body>
</html>