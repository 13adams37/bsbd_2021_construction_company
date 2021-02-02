<?php
	require_once 'includes/sessions.php';
	require_once 'includes/crypt.php';
	if (mySession_start())
	{
		header("location: lk.php");
	}
?>
<?php
	if (isset($_POST['rega'])) 
	{
		$error = FALSE;
		if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} }
		if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
		$login = htmlspecialchars(trim($_POST['login'])); 
		$password = htmlspecialchars(trim($_POST['password']));
		$checkpw = htmlspecialchars(trim($_POST['checkpw']));
		$name = htmlspecialchars(trim($_POST['name']));
		$adr = htmlspecialchars(trim($_POST['adr']));
		$phonen = htmlspecialchars(trim($_POST['phonen']));
		$company = htmlspecialchars(trim($_POST['company']));
		$card = htmlspecialchars(trim($_POST['card']));
		if (empty($login) or empty($password) or empty($checkpw) or empty($name) or empty($adr) or empty($phonen) or empty($card)){
			$error = TRUE;
			echo "Заполните данные!";
		}
		if ($password != $checkpw){
			$error = TRUE;
			echo "Пароли не совпадают!"; 
		}
		$result = $db->query("SELECT acc_id FROM usadba_accounts WHERE acc_email='$login'");
		$myrow = $result->fetch(PDO::FETCH_ASSOC);
		if (!empty($myrow['acc_id'])) {
			$error = TRUE;
			echo "Данная почта уже загерестрирована!";
		}
		if(filter_var($login, FILTER_VALIDATE_EMAIL) == FALSE){
			$error = TRUE;
			echo 'Неверно введен е-mail';  
		}
		if ($error == FALSE){
			$sql = 'INSERT INTO usadba_accounts (acc_email, acc_password, acc_registration) VALUES (:acc_email, :acc_password, NOW())';
			$params = [ ':acc_email' => $login, ':acc_password' => password_hash($password, PASSWORD_BCRYPT)];
			$stmt = $db->prepare($sql);
			$stmt->execute($params);
			
			$sql = 'SELECT acc_id FROM usadba_accounts WHERE acc_email = :login';
 			$params = [':login' => $login];
 			$stmt = $db->prepare($sql);
 			$stmt->execute($params);

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$user = $user.$row['acc_id'];
			}
			
			$sql = 'INSERT INTO usadba_users (u_id, u_name, u_address, u_phone, u_company) VALUES (:u_id, :u_name, :u_address, :u_phone, :u_company)';
			$params = [ ':u_id' => $user, ':u_name' => $name, ':u_address' => $adr, ':u_phone' => $phonen, ':u_company' => $company ];
			$stmt = $db->prepare($sql);
			$stmt->execute($params);
			
			$sql1 = 'INSERT INTO usadba_availability (acc_id, card_number) VALUES (:acc_id, :card_number)';
			$params1 = [ ':acc_id' => $user, ':card_number' => encrypt($card) ];
			$stmt1 = $db->prepare($sql1);
			$stmt1->execute($params1);
			
			
			echo "Вы успешно зарегестрировались! Теперь авторизуйтесь! <a href='login.php'>Авторизация</a>";
		}
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
 			Почта: <input type="text" name="login" />
 			Пароль: <input type="password" name="password" />
			Повторите пароль: <input type="password" name="checkpw" />
			ФИО: <input type="text" name="name" />
			Адрес: <input type="text" name="adr" />
			Телефон: <input type="text" name="phonen" />
			Компания: <input type="text" name="company" />
			Номер карты: <input type="text" name="card" />
 			<input class="button-green" type="submit" value="Зарегестрироваться" name="rega" />
		</form>
	</div>
    <div class="footer">
        <div class="wrap">
            <div class="copy">2021 &copy; <a href="index.php">Мари Усадьба</a><br />Деревянное домостроение по всей России</div>
        </div>		
    </div>
</body>
</html>