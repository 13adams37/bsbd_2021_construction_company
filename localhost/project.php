<?php
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
        <div class="logo"><a href="about.php"></a></div>
        <div class="name">Мари Усадьба</div>
        <div class="slogan">качество доступное не всем</div>
        <a class="more" href="#title"></a>
    </div>
</div>

<h1 class="title" id="title" style="margin-top: 50px;">Проекты домов</h1>
<div class="sub">Деревянные дома, бани и другие постройки</div>
<div class="photogal">
<ul>
    <li><img src="img/project/img_1444393570_264743.jpg" width="250" height="188"/><span>Проект дома Мариец</span></li>
    <li><img src="img/project/img_1444393508_494387.jpg" width="250" height="188"/><span>Проект дома Пентагон</span></li>
    <li><img src="img/project/img_1444393435_244324.jpg" width="250" height="188"/><span>Проект дома Сомбатхей</span></li>
    <li><img src="img/project/img_1444393319_208031.jpg" width="250" height="188"/><span>Проект бани Тимофей</span></li>
    <li><img src="img/project/img_1444393205_426508.jpg" width="250" height="188"/><span>Проект дома Павел</span></li>
    <li><img src="img/project/img_1444393077_368177.jpg" width="250" height="188"/><span>Проект дома Даниил</span></li>
    <li><img src="img/project/img_1444392970_770339.jpg" width="250" height="188"/><span>Проект бани Ночная</span></li>
    <li><img src="img/project/img_1444392889_315165.jpg" width="250" height="188"/><span>Проект бани Йошкар</span></li>
    <li><img src="img/project/img_1444392581_510460.jpg" width="250" height="188"/><span>Проект дома Ола</span></li>
    <li><img src="img/project/img_1444392433_94295.jpg" width="250" height="188"/><span>Проект бани Гомзово</span></li>
    <li><img src="img/project/img_1444392325_973201.jpg" width="250" height="188"/><span>Проект бани Девятый</span></li>
    <li><img src="img/project/img_1444392184_17702.jpg" width="250" height="188"/><span>Проект бани Медведево</span></li>
    <li><img src="img/project/img_1444392073_149560.jpg" width="250" height="188"/><span>Проект бани Сучков</span></li>
    <li><img src="img/project/img_1444391865_913542.jpg" width="250" height="188"/><span>Проект дома Дмитрий</span></li>
    <li><img src="img/project/img_1444391197_946525.jpg" width="250" height="188"/><span>Проект дома Сергеевич</span></li>
</ul>
<div class="clear"></div>
<br />
<br />
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