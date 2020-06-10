<?php $this->layout('layout/admin', ['title' => 'Звонки и обращения', 'auth' => $auth, 'allcalls' => $allcalls, 'isInHistory' => $isInHistory, 'users' => $users, 'role' => $role]); 

function GetEventType($eventCode)
{
	switch($eventCode)
	{
		case 1: return "Входящий"; break;
		case 2: return "Отвечено"; break;
		case 3: return "Преветственное сообщ"; break;
		case 4: return "Получен DTMF"; break;
		case 5: return "Получено сообщ о факсе (Т.38)"; break;
		case 6: return "Получено сообщ о факсе (FaxTone)"; break;
		case 7: return "Запуск голос меню"; break;
		case 8: return "Нач переадресации"; break;
		case 9: return "Результ переадр"; break;
		case 10: return "Нач переадресации (корот ном)"; break;
		case 11: return "Рез переадр (корот ном)"; break;
		case 12: return "Поступление вызова"; break;
		case 13: return "Нач зап разговора"; break;
		case 14: return "Конец записи разговора"; break;
		case 15: return "Завершение вызова"; break;
		case 16: return "Подготовка соед абон и оператора"; break;
	}
}

function GetServiceType($serviceType)
{
	switch($serviceType)
	{
		case 1: return "<img src='https://aa.mts.ru/UnAdmin/images/logo.svg' style='height:20px; '>"; break;
		case 2: return "Автосекретарь исх."; break;
		case 3: return "Контр качест"; break;
		case 4: return "Автосекретарь (зам АОН)"; break;
		case 5: return "<i class='fas fa-phone-square-alt'></i>"; break;
		case 6: return "<i class='fab fa-whatsapp-square'></i>"; break;
		case 7: return "<i class='fas fa-envelope-square'></i>"; break;
		case 8: return "Другое"; break;
	}
}

function isDone($id)
{
	switch($id)
	{
		case 0: return "Нет"; break;
		case 1: return "Да"; break;
	}
}

function Dedline($time)
{
	if (($time !== NULL) && (strtotime($time) < strtotime('now'))){
		echo 'red';
	}
}

function TimeBetween($time)
{
	$start = date('d-m-Y 21:00', strtotime('now -1 day'));
	$end = date('d-m-Y 07:00', strtotime('now'));
	if((strtotime($time) >= strtotime($start)) && (strtotime($time) <= strtotime($end))){
		echo ' blue';
	}
}

require './lib/autonumber.php';

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="/js/calls.js"></script>

<script>
	$(function() {
		var numbers = <?php echo $numbers ;?>;
		$(".modal-body form input#AutoNumber").autocomplete({
			source: numbers
		});
	});
</script>

<main class="py-4">

    <div class="container-fluid">
	
	<div class="btn-group" role="group" aria-label="Basic example" style="margin: 15px 0px;">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCallModal">
			<i class="fas fa-plus-circle"></i> Создать вручную
		</button>
	</div>

	
        <?php echo flash()->display();?>

        <div id="result"></div>
		
		<h1 class="mt-5 mb-5">
			Список вызовов и обращений 
			<a class="published" href="/calls">
				<i class="fas fa-low-vision" style="font-size: 28px;"></i> 
			</a>
			<a class="published" href="?published=all">
				<i class="far fa-eye" style="font-size: 28px;"></i>
			</a>
		</h1>
		
        <table id="calls" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Источник</th>
                <th>Время</th>
                <th>Номер</th>
                <th>Телефон горячей линии</th>
                <!--<th>Номер переадр</th>
                <th>Номер переадр опер.</th>
                <th>Кор ном переад.</th>
                <th>Кор ном переад опер.</th>
                <th>DTMF</th>
                <th>Generic</th>
                <th>Результат</th>
                <th>Тип события</th>-->
                <th>Номер автомата</th>
                <th>Название точки</th>
                <th>Причина обращения</th>
                <th>Компенсация</th>
                <th>Ответственный</th>
                <th>Дедлайн</th>
                <th>Выполнено</th>
                <th>Комментарии</th>
                <th>Кто редактировал</th>
                <th>Редактировать</th>
            </tr>
            </thead>

            <tbody>
				<?php foreach($allcalls as $i => $call): ?>
					<?php //$pnum = substr_replace($call["AN"], "7", 0, 1); ?>
				<tr class="<?php Dedline($call["Deadline"]); TimeBetween(date('d-m-Y H:i', strtotime($call["EventTime"])));?>">
					<td><?=GetServiceType($call["ServiceType"]);?></td>
					<td>
						<span style="display: none;"><?=strtotime($call["EventTime"]);?></span>
						<?=date('d.m.Y - H:i:s', strtotime($call["EventTime"]));?>
					</td>
					<td>
						<a href="tel:+<?=$call["AN"];?>">+<?=$call["AN"];?></a><br>
						<?php if(in_array($call["AN"],$isInHistory)) :?>
						<small>
							<a id="btnHistory" data-toggle="modal" data-target=".oneHistory" href="/admin/history/<?=$call["AN"];?>">История</a>
						</small>
						<?php endif;?>
					</td>
					<td><?=$call["UN"];?></td>
					<!--<td><?=$call["DN1"];?></td>
					<td><?=$call["DN2"];?></td>
					<td><?=$call["EXT1"];?></td>
					<td><?=$call["EXT2"];?></td>
					<td><?=$call["DTMF"];?></td>
					<td><?=$call["Generic"];?></td>
					<td><?=$call["Result"];?></td>
					<td><?=GetEventType($call["EventType"]);?></td>-->
					<td><?=$call["AutoNumber"];?></td>
					<td><?=$call["PointName"];?></td>
					<td><?=$call["PetitionReason"];?></td>
					<td><?=$call["Compensation"];?></td>
					<td><?=$call["Responsible"];?></td>
					<td><?=$call["Deadline"];?></td>
					<td>
						<?=isDone($call["isDone"]);?>
						<div>
							<?php if($call["isDone"] == 1){
								echo date('d.m.Y - H:i:s', strtotime($call["completed"]));
							}
							?>
						</div>
					</td>
					<td><?=$call["Comments"];?></td>
					<td><?=$call["whoEdit"];?></td>
					<!-- <td><a href="/calls/<?=$call["id"];?>/edit"><button type="button" class="btn btn-secondary"><i class="fas fa-edit"></i> Редактировать</button></a></td> -->
					<td>
						<a id="oneHistory" class="Edit" data-toggle="modal" data-target=".oneHistory" href="/calls/<?=$call["id"];?>/edit">
							<i class="fas fa-edit"></i>
						</a> 
						<?php if($role[1] == 'ADMIN'):?>
						<a class="del" href="" data-id="<?=$call["id"];?>">
							<i class="far fa-trash-alt"></i>
						</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach; ?>
            </tbody>
        </table>
		
    </div>
	
	
	<div class="modal fade" id="addCallModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Добавление обращения</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="POST">
						<input type="hidden" class="form-control" id="inputAuthor" value="<?php echo $_SESSION['auth_user_id'];?>">
						<div class="form-group">
							<label for="inputTel">Источник</label>
							<select name="ServiceType" class="form-control" required>
								<option value="5">Звонок</option>
								<option value="6">WhatsApp</option>
								<option value="7">Почта</option>
								<option value="8">Другое</option>
							</select>
						</div>
						<!-- DATE -->
						<div class="form-group">
							<label for="inputNumAutomat">Номер телефона клиента</label>
							<input type="text" class="phone_number form-control" name="AN" id="inputTel" placeholder="79999999999" required maxlength="12">
							<small id="telHelp" class="form-text text-muted">Номер телефона в формате 79999999999</small>
						</div>
						<!-- Operator -->
						<div class="form-group">
							<label for="inputSumPromo">Номер автомата</label>
							<input type="text" class="form-control" name="AutoNumber" id="AutoNumber">
						</div>
						<div class="form-group">
							<label for="inputSumPromo">Название точки</label>
							<input type="text" class="form-control" name="PointName">
						</div>
						<div class="form-group">
							<label for="inputSumPromo">Причина обращения</label>
							<input type="text" class="form-control" name="PetitionReason">
						</div>
						<div class="form-group">
							<label for="inputSumPromo">Компенсация</label>
							<input type="number" class="form-control" name="Compensation">
						</div>
						<div class="form-group">
							<label for="inputSumPromo">Ответственный за выполнение</label>
							<select name="Responsible" class="form-control" required>
								<?php foreach($users as $user): ?>
								<option><?=$user["username"];?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="inputSumPromo">Дедлайн</label>
							<input type="text" id="datetimepicker_hand" class="form-control" name="Deadline" placeholder="дд.мм.гггг">
						</div>
						<div class="form-group">
							<label for="inputSumPromo">Задача выполнена</label>
							<select name="isDone" class="form-control" required>
								<option value="0">Нет</option>
								<option value="1">Да</option>
							</select>
						</div>
						<div class="form-group">
							<label for="inputSumPromo">Комментарии</label>
							<textarea class="form-control" name="Comments"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Cоздать</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div id="oneHistory" class="modal fade oneHistory" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	  </div>
	</div>
	
	<script>
		$(document).ready(function() {
			$('#calls').dataTable( {
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
				},
				"aaSorting": [[ 1, "desc" ]],
				"pageLength": 25
			} );
		} );
	</script>
	
	<script>
		$('#oneHistory, .Edit').on('shown.bs.modal', function (e) {
			var href = e.relatedTarget.href;
			console.log(href);
			$('#oneHistory .modal-body').load(href);
		});
		$('#oneHistory').on('hidden.bs.modal', function (e) {
			$('#oneHistory .modal-body').html('');
		});
	</script>
	<script>
		$('#datetimepicker_hand').datetimepicker({
			format:'d.m.Y H:i',
			startDate:'+1971.05.01',
			lang:'ru'
		});
	</script>
	
</main>
