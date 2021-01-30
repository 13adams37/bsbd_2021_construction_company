<?php
	require_once 'includes/db.php';
	require_once 'includes/sessions.php';
	
	if (!mySession_start())
	{
		exit("<br><a href='login.php'>Авторизация</a><br> Необходимо авторизоватсья!");
	}

	if (isset($_GET['action']) && $_GET['action']=="add")
	{
		if (isset($_COOKIE['SESSID'])) 
		{				
			$sql = 'SELECT action_id, action_title, action_price FROM usadba_action WHERE action_id = :action_id';
			$params = [ ':action_id' => strval(trim($_GET['id'])) ];
			$stmt = $db->prepare($sql);
			$stmt->execute($params);
			$action = $stmt->fetch(PDO::FETCH_OBJ);

			if ($action)
			{
				// информация об аккаунте
				$sql_acc = 'SELECT acc_id FROM usadba_session WHERE session_id = :sess_id';
				$stmt_acc = $db->prepare($sql_acc);
				$stmt_acc->execute([':sess_id' => $_COOKIE['SESSID']]);
				$acc = $stmt_acc->fetch(PDO::FETCH_OBJ);

				$get_order = $db->prepare('SELECT * FROM usadba_cart INNER JOIN usadba_action using (action_id) WHERE session_id = :sess_id AND acc_id = :acc_id AND action_title = :action_title AND action_id = :action_id' );
			
				$get_order->execute([ ':sess_id' => $_COOKIE['SESSID'], ':acc_id' => $acc->acc_id, ':action_title' => $action->action_title,  ':action_id' => $action->action_id ]);
				$order = $get_order->fetch(PDO::FETCH_OBJ);

				if ($order)
				{
					// обновляем текущую позицию
					$add_cart_sql = 'UPDATE usadba_cart SET count = :new_count
									 WHERE acc_id = :acc_id AND action_id = :action_id';
					$add_cart_params = [ 
										 ':new_count' => $order->count + 1, 
										 ':acc_id' => $acc->acc_id, 
										 ':action_id' => $action->action_id
									   ];
				}
				else
				{
					$add_cart_sql = 'INSERT INTO usadba_cart (session_id, price, count, action_id, acc_id) 
								 	 VALUES (:sess_id, :price, :count, :action_id, :acc_id)';
					$add_cart_params = [ ':sess_id' => $_COOKIE['SESSID'], 
										 ':price' => $action->action_price, 
										 ':count' => 1, 
										 ':action_id' => $action->action_id, 
										 ':acc_id' => $acc->acc_id
									   ];
				}
				$stmt = $db->prepare($add_cart_sql);
				$stmt->execute($add_cart_params);
			}
		}
	}

	if (isset($_GET['action']) && $_GET['action']=="drop")
	{
		$sql = 'DELETE FROM usadba_cart WHERE session_id = :sess_id AND action_id = :action_id';
		$params = [  'sess_id' => $_COOKIE['SESSID'],
				     ':action_id' => strval(trim($_GET['id'])) 
				  ];
		$stmt = $db->prepare($sql);
		$stmt->execute($params);
	}	

    if (isset($_POST['buy']))
    { 
    	$get_items = 'SELECT * FROM usadba_cart WHERE session_id = :sess_id';
    	$stmt = $db->prepare($get_items);
    	$stmt->execute([ ':sess_id' => $_COOKIE['SESSID']]);
    	$order = $stmt->fetch(PDO::FETCH_OBJ);
		if ($order)
		{	
			$acc_id = $order->acc_id;			
			$cur_money_sql = 'SELECT * FROM usadba_cart INNER JOIN usadba_availability USING (acc_id) WHERE acc_id = :acc_id';
			$stmt = $db->prepare($cur_money_sql);
			$stmt->execute([':acc_id' => $acc_id]);
			$cur_money = $stmt->fetch(PDO::FETCH_OBJ);
			$amount = $cur_money->amount;
			
			$admin_id = '01a3a7c0-a951-4267-97e5-7237c621ac32';		//00091e55-bdc5-40ea-a358-ecb6eb1bdc1a	
			
			$sql = 'SELECT * FROM usadba_cart INNER JOIN usadba_action using (action_id) WHERE session_id = :sess_id';
        	$stmt = $db->prepare($sql);
        	$stmt->execute([ ':sess_id' => $_COOKIE['SESSID'] ]);
			
			$totalprice = 0;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
			{
				$totalprice += $row['price'] * $row['count'];
			}			
			if ($amount >= $totalprice)
			{
				$succes = 1;
				try{					
					$db->beginTransaction();
					$stmt = $db->prepare('UPDATE usadba_availability SET amount = amount - :totalprice WHERE acc_id = :acc_id');
					$stmt->execute([':totalprice' => $totalprice, ':acc_id' => $acc_id]);
					
					$stmt = $db->prepare('UPDATE usadba_availability SET amount = amount + :totalprice WHERE acc_id = :acc_id');
					$stmt->execute([':totalprice' => $totalprice, ':acc_id' => $admin_id]);					
					
					$order_id = uniqid();

					$update_cart = 'UPDATE usadba_cart SET session_id = :new_sess_id, order_id = :order_id WHERE session_id = :sess_id';
					$stmt = $db->prepare($update_cart);
					$stmt->execute([':new_sess_id' => NULL, ':sess_id' => $_COOKIE['SESSID'], ':order_id' => $order_id]);

					$add_order = 'INSERT INTO usadba_orders (order_id, order_status, user_id, full_price) 
								  SELECT
										:order_id AS order_id,
										"Обработка заказа" AS order_status,
										usadba_accounts.acc_id AS user_id,
										:full_price AS full_price
								  FROM usadba_cart
								  INNER JOIN usadba_accounts
								  ON usadba_accounts.acc_id = usadba_cart.acc_id 
								  LIMIT 1
								 ';
					$stmt = $db->prepare($add_order);
					$stmt->execute([':order_id' => $order_id, ':full_price'=>$totalprice]);
					$db->commit();
					echo "Заказ оформлен успешно";
				} 
				catch (Exception $e) {
					$db->rollBack();
					echo "Ошибка: недостаточно средств" . $e->getMessage();
					$succes = 0;
				}			
			}
			else{
				echo "Ошибка: Недостаточно средств";
			}
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
			<div class="content">
				<div class="wrapper">
					<h1>Ваши заказы</h1> 
					<form method="post" action="order.php?page=order"> 
						<table> 
          					<tr> 
            					<th>Название</th> 
            					<th>Количество</th> 
            					<th>Цена</th> 
            					<th>Сумма</th> 
        					</tr> 

        					<?php 

								$sql = 'SELECT * FROM usadba_cart INNER JOIN usadba_action using (action_id) WHERE session_id = :sess_id';
        						$stmt = $db->prepare($sql);
        						$stmt->execute([ ':sess_id' => $_COOKIE['SESSID'] ]);

        						$products = '';
        						$counts = 0;
        						$totalprice = 0; 

       
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
								{
									$totalprice += $row['price'] * $row['count'];
									
							?>
								<tr> 
        							<td><?php echo $row['action_title'] ?></td> 
        						    <td><?php echo $row['count'] ?></td>
        							<td><?php echo $row['price'] ?> ₽</td> 
        							<td><?php echo $row['price'] * $row['count'] ?> ₽</td> 
        							<td> <a class="button" 
        								    href="order.php?page=order&action=drop&id=<?php echo $row['action_id'] ?>">
        									Убрать
        								 </a>  
        							</td>
        						</tr>

        						<?php } ?>

        					<tr> 
                       	 		<td colspan="4">К оплате: <?php echo $totalprice  ?> ₽</td> 
                    		</tr> 

                    		<button type="submit" name="buy">Оформить заказ</button> 
   
        				</table>
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