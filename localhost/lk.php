<?php
	require_once 'includes/sessions.php';
	require_once 'includes/crypt.php';
	require_once 'includes/db.php';
	if (!mySession_start())
	{
		header("location: login.php");
	}
	$sql = 'SELECT * FROM usadba_users 
								INNER JOIN usadba_accounts ON usadba_accounts.acc_id = usadba_users.u_id 
								INNER JOIN usadba_session ON usadba_session.acc_id = usadba_accounts.acc_id 
								INNER JOIN usadba_availability ON usadba_availability.acc_id = usadba_accounts.acc_id
								WHERE usadba_session.session_id = :sess_id';
						$stmt = $db->prepare($sql);
 						$stmt->execute([':sess_id' => $_COOKIE['SESSID']]);
 						$user = $stmt->fetch(PDO::FETCH_OBJ);
?>
<?php
	 if (isset($_POST['pay_card']))
	 {
		$sum = $_POST['sum'];
		$sql = 'UPDATE usadba_availability SET amount = amount + :sum WHERE acc_id = :acc_id';
		$params = [':sum' => $sum, ':acc_id' => $user->acc_id];
		$stmt = $db->prepare($sql);		
		$stmt->execute($params);
		header("location: lk.php");
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
					<li><a href="order.php">Заказы</a></li>
					<li><a href="logout.php">Выход</a></li>
					<!-- LK -->
                </ul>
            </div>
        </div>
		<div class="content">
			<div class="wrapper">
				<b>Добро пожаловать!</b>
					<?php 
 						echo '<br>Вы вошли на сайт, как <u>'.$user->u_name.'</u>
 							  <br>На вашем счету '.$user->amount.' ₽
 							 ';

 						if ($user->u_role == 'admin')
 						{
 							echo "<br><br>Меню:
 							  <br><a href='orders.php '> <B><u>Заказы</u></B></a>
							  <br><a href='userlist.php '> <B><u>Список пользователей</u></B></a>
 							";
 						
 						}		
					?>
					
				<p> Пополнить счет: </p>
				<p> Ваша карта: <?php echo decrypt($user->card_number); ?></p>
				<form method="post" action="lk.php?page=lk">
					<p> Введите сумму <input type="text" name="sum" class="hey"></p>
					<p><button type="submit" name="pay_card">Пополнить</button></p>
				</form>					
			</div>
		</div>
    <div class="footer">
        <div class="wrap">
            <div class="copy">2021 &copy; <a href="index.php">Мари Усадьба</a><br />Деревянное домостроение по всей России</div>
        </div>		
    </div>
	</body>
</html>