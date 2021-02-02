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

	if (isset($_GET['action']) && $_GET['action']=="drop")
	{ 	
		try
		{			
			$db->beginTransaction();			
				$sql = 'SELECT * FROM usadba_orders INNER JOIN usadba_cart USING(order_id) INNER JOIN usadba_availability USING (acc_id) WHERE usadba_orders.order_id = :order_id LIMIT 1';
				$params = [ ':order_id' => strval(trim($_GET['id'])) ];
				$stmt = $db->prepare($sql);
				$stmt->execute($params);
				$order_info = $stmt->fetch(PDO::FETCH_ASSOC);
				$order_status = $order_info['order_status'];

				$acc_id = $order_info['acc_id'];
				
				$full_price = $order_info['full_price'];
				$admin_id = '00091e55-bdc5-40ea-a358-ecb6eb1bdc1a';
				
			if ($order_status != "Готово")
			{
				
				$sql = 'UPDATE usadba_availability SET amount = amount + :amount WHERE acc_id = :id';
				$params = [ ':amount' => $full_price, ':id' => $acc_id ];
				$stmt = $db->prepare($sql);
				$stmt->execute($params);
				
				$sql = 'UPDATE usadba_availability SET amount = amount - :amount WHERE acc_id = :acc_id';
				$params = [ ':amount' => $full_price, ':acc_id' => $admin_id ];
				$stmt = $db->prepare($sql);
				$stmt->execute($params);	
			}
			$sql = 'DELETE FROM usadba_cart WHERE order_id = :order_id';
			$params = [ ':order_id' => strval(trim($_GET['id'])) ];
			$stmt = $db->prepare($sql);
			$stmt->execute($params);
			$db->commit();
		}
		
		catch (Exception $e) {
					$db->rollBack();
					echo $e->getMessage();
					
				}
		header("location: orders.php");
	}

	if (isset($_GET['action']) && $_GET['action']=="update")
	{
		$sql = 'UPDATE usadba_orders SET order_status = "Готово" WHERE order_id = :order_id';
		$params = [ ':order_id' => strval(trim($_GET['id'])) ];
		$stmt = $db->prepare($sql);
		$stmt->execute($params);
		
		header("location: orders.php");
		
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
					<h1>Список заказов</h1> 
					<table> 
          					<tr> 
            					<th>Идентификатор</th> 
            					<th>Наименования</th> 
            					<th>Количество</th> 
            					<th>Статус</th> 
            					<th>Сумма</th> 
        					</tr>

							<?php
								$sql = 'SELECT *  FROM usadba_orders
										INNER JOIN usadba_cart ON usadba_cart.order_id = usadba_orders.order_id INNER JOIN usadba_action ON usadba_cart.action_id = usadba_action.action_id
									   ';

								$stmt = $db->query($sql);

								$totalorder = array('id' => 0, 'price' => 0, 'status' => '');
								$sql = 'SELECT *  FROM usadba_orders
										INNER JOIN usadba_cart ON usadba_cart.order_id = usadba_orders.order_id INNER JOIN usadba_action ON usadba_cart.action_id = usadba_action.action_id
									   ';

								$stmt = $db->query($sql);

								$totalorder = array('id' => 0, 'price' => 0, 'status' => '');

								while ($order = $stmt->fetch(PDO::FETCH_OBJ)) 
								{
							?>		
									<tr>
										<td> 
											<?php 
												if ($totalorder['id'] != $order->order_id) 
												{	
													echo $order->order_id; 
												}
												else
													echo "";
											?> 
										</td>

										<td>	
											<?php echo $order->action_title ?>
										</td>

										<td>
											<?php echo $order->count ?>
										</td>

										<td>
											<?php 
												if ($totalorder['id'] !=  $order->order_id) 
												{
													$totalorder['status'] = $order->order_status;
													echo $order->order_status;
												}
											?>
										</td>

										<td>
											<?php
												$totalorder['price'] += $order->price * $order->count;
												if ($totalorder['id'] != $order->order_id) 
												{
													echo $order->full_price.' ₽';
												}
											?>
										</td>

										<td>
											<?php 
												if ($totalorder['id'] != $order->order_id) 
												{
													$totalorder['status'] = '';
													$totalorder['id'] = $order->order_id;
											?>
											

													<a 	class="button" 
													   	href="orders.php?page=orders&action=drop&id=<?php echo  $totalorder['id'] ?>" >
        												<?php
														if ($order->order_status == "Готово")
																echo "Убрать из списка";
														else echo "Отменить заказ"?>
        											</a> 

        											<a 	class="button" 
        												href="orders.php?page=orders&action=update&id=<?php echo  $totalorder['id'] ?>" >
        												Изменить статус заказа
        											</a> 
        								
										  <?php } ?>
										
										</td>
									</tr>
						  <?php } ?>
		
        			</table>
				</div>
			</div>
    <div class="footer">
        <div class="wrap">
            <div class="copy">2021 &copy; <a href="index.php">Мари Усадьба</a><br />Деревянное домостроение по всей России</div>
        </div>		
    </div>
</body>
</html>