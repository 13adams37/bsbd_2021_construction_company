<?php
	require_once 'includes/sessions.php';
	
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

	if ($user->u_role != 'admin')
 	{
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
	<div class="content">
		<div class="tablo">
			<h1>Список юзеров</h1> 
			<table>
          		<tr> 
            		<th>Идентификатор</th> 
            		<th>Имя</th> 
					<th>e-mail</th>
            		<th>Адрес</th> 
					<th>Телефон</th>
					<th>Дата регистрации</th>
            		<th>Роль</th> 
        		</tr>
				<?php
					if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}

					$size_page = 20;
					$offset = ($page-1) * $size_page;

					$result = $db->query("SELECT COUNT(*) FROM `usadba_accounts`");
					$total_rows = $result->fetch(PDO::FETCH_BOTH)[0];
					$total_pages = ceil($total_rows / $size_page);
					
					$res_data = $db->query("SELECT * FROM usadba_users INNER JOIN usadba_accounts ON usadba_users.u_id = usadba_accounts.acc_id ORDER BY usadba_accounts.acc_registration DESC LIMIT $offset, $size_page");
					while($row = $res_data->fetch(PDO::FETCH_ASSOC))
					{
					?>
						<tr> 
						<td>
						<?php echo $row['u_id']; ?>
						</td> <td>
						<?php echo $row['u_name']; ?>
						</td> <td>
						<?php echo $row['acc_email']; ?>
						</td> <td>
						<?php echo $row['u_address']; ?>
						</td> <td>
						<?php echo $row['u_phone']; ?>
						</td> <td>
						<?php echo $row['acc_registration']; ?>
						</td> <td>
						<?php echo $row['u_role']; ?>
						</td>
						</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	
    <div class="navnav">
        <a href="?page=1">Первая</a>
        <a class="<?php if($page <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Предыдущая</a>
        </a>
        <a class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Следущая</a>
        </a>
        <a href="?page=<?php echo $total_pages; ?>">Последняя</a>
		
    </div>


    <div class="footer">
        <div class="wrap">
            <div class="copy">2021 &copy; <a href="index.php">Мари Усадьба</a><br />Деревянное домостроение по всей России</div>
        </div>		
    </div>
</body>
</html>
