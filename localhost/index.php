<?php
	require_once 'includes/db.php';
	require_once 'includes/sessions.php';
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
		<script src="js/item.js" language="javascript"></script>
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
		<div class="bground">
			<video autoplay loop muted>
				<source src="images/video.mp4" type="video/mp4" />
			</video>
		<div class="area">
			<div class="logo"><a href="index.php"></a></div>
			<div class="name">Мари Усадьба</div>
			<div class="slogan">качество доступное не всем</div>
			<a class="more" href="#serv"></a>
		</div>
	</div>
	<div class="serv" id="serv">
		<div class="wrap">
			<div class="head">
				<div class="name">Наша специализация</div>
				<div class="text">Основные направления деятельности</div>
			</div>
			<div class="area">
            <!-- 
			<a class="ocb"> <strong>Оцилиндрованное бревно</strong><span>Производство и продажа оцилиндрованного бревна в пагонаже или деталях. Финский или лунный паз.</span><em>8 000 руб./м3</em></a>
            <a class="srub"> <strong>Рубленные срубы</strong><span>Строительство рубленных домов и бань по всей России. Высокое качество рубки, проектирование.</span><em>1 000 руб./м3</em></a>
            <a class="profile"><strong>Профилированный брус</strong><span>Производство и продажа профилированного бруса под проекты с чашей или без чаш. </span><em>10 200 руб./м3</em></a>
            <a class="pilomat"><strong>Пиломатериалы</strong><span>Производство и продажа различных пиломатериалов. Обрезная доска, строганая половая, евровагонка.</span><em>7 000 руб./м3</em></a>
			-->
				<?php 
					$result = $db->query("SELECT * FROM `usadba_action`");
					$items = '';
					$about = '';
					$count = $result->rowCount();
					$price = '';
					$img = '';
					$action_id = '';
							
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
						$items = $items.', '.$row['action_title'];
						$about = $about.', '.$row['action_about'];
						$price = $price.', '.$row['action_price'];
						$img = $img.', '.$row['action_img'];
						$action_id = $action_id.', '.$row['action_id'];
					}
					echo '<script type="text/javascript"> 
						action_tbl("'.$count.'", "'.$items.'", "'.$about.'", "'.$price.'", "'.$img.'", "'.$action_id.'"); 
						</script>';  
					$result = null;
				?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="preim" id="preim">
		<a class="next" href="#preim"></a>
		<div class="wrap">
			<div class="head">
				<div class="name">Наши преимущества</div>
				<div class="text">Почему работать с нами выгодно</div>
			</div>
			<div class="area">
				<div class="item pos-1"><div class="cont"><span></span><strong>0 лет на рынке</strong> деревообработки и домостроения</div></div>
				<div class="item pos-2"><div class="cont"><span></span><strong>Более 0 проектов</strong> домов и бань сданных под ключ</div></div>
				<div class="item pos-3"><div class="cont"><span></span><strong>Собственная линия</strong> переработки леса и пиломатериалов</div></div>
				<div class="item pos-4"><div class="cont"><span></span><strong>Не качественный лес</strong> только из республики Марий-Эл</div></div>
				<div class="item pos-5"><div class="cont"><span></span><strong>Индивидуальное</strong> проектирование под заказчика</div></div>
				<div class="item pos-6"><div class="cont"><span></span><strong>Не экологичность</strong> наших домов проверена временем</div></div>
				<div class="item pos-7"><div class="cont"><span></span><strong>Доставка срубов</strong> по всему миру</div></div>
				<div class="item pos-8"><div class="cont"><span></span><strong>Профессионализм</strong> самый худший подход к каждому клиенту</div></div>
				<div class="item pos-9"><div class="cont"><span></span><strong>Без гарантий</strong> на все дома возведенные нашей компанией</div></div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="works" id="works">
		<a class="next" href="#works"></a>
		<div class="wrap">
			<div class="head">
				<div class="name">Наши проекты</div>
				<div class="text">Дома из рубленного и оцилиндрованного бревна</div>
			</div>
			<div class="area">
            <a class="item" title=""><img src="img/works/photo_4_56ec4bfaddee3.jpg" /></a>
        	<a class="item" title=""><img src="img/works/photo_4_56ee58dda2116.jpg" /></a>
        	<a class="item" title=""><img src="img/works/photo_4_56ec4dea77f7e.jpg" /></a>
        	<a class="item" title=""><img src="img/works/photo_4_56ec4d1473d47.jpg" /></a>
        	<a class="item" title=""><img src="img/works/photo_4_56ec4fa6e7e2c.jpg" /></a>
        	<a class="item" title=""><img src="img/works/photo_4_56ec53e2eda34.jpg" /></a>
			</div>
		</div>
	</div>
	<div class="contacts" id="contacts">
		<a class="up" href="#home"></a>
	</div>
    <div class="footer">
        <div class="wrap">
            <div class="copy">2021 &copy; <a href="index.php">Мари Усадьба</a><br />Деревянное домостроение по всей России</div>
        </div>		
    </div>
	</body>
</html>