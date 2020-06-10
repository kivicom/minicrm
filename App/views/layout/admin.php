<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin - <?=$this->e($title)?></title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="/js/jquery.datetimepicker.full.js"></script>
	<link href="/css/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css">
        

    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script>
        $(function() {
            $('.phone_number').mask('7(999) 999 99 99');
        });
    </script>
    <!-- /jQuery Mask Plugin -->

    <script src="js/script.js"></script>

    <!-- Styles -->
    <link href="css/style.css" rel="stylesheet">

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <?php echo (isset($_SESSION['user']['name'])) ? $_SESSION['user']['name'] : '' ;?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    <?php if($this->e($auth)): ?>
                        <li class="nav-item"><a class="nav-link" href="/logout">Выход</a></li>
						<?php if ($role[1] == 'ADMIN'):?>
							<li class="nav-item">
								<a class="nav-link" href="../admin/register">Добавить администратора</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="../admin/cnfapi">Настройки API sms.ru</a>
							</li>
							<?php endif;?>
						<li class="nav-item">
							<a class="nav-link" href="/">Система управления промокодами</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/calls">Список вызовов и обращений</a>
                        </li>
						<li class="nav-item">
                            <a class="nav-link" href="/admin/history">История</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../auth">Авторизация</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php echo $this->section('content')?>

</div>
</body>
</html>
