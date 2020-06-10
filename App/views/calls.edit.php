<?php 

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
		case 1: return "Автосекретарь"; break;
		case 2: return "Автосекретарь исх."; break;
		case 3: return "Контр качест"; break;
		case 4: return "Автосекретарь (зам АОН)"; break;
		case 5: return "Звонок"; break;
		case 6: return "WhatsApp"; break;
		case 7: return "Почта"; break;
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

require './lib/autonumber.php';
		
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="/js/smscalls.js"></script>

<script>
	$(function() {
		var numbers = <?php echo ($numbers !== null) ? ($numbers) : [] ;?>;
		$("form input#AutoNumber").autocomplete({
			source: numbers
		});
	});
</script>

<main class="py-4">

    <div class="container">
	
        <?php echo flash()->display();?>

        <div id="result"></div>
		
		<h1 class="mt-5 mb-5">Редактирование обращения №<?=$currentCall["id"];?></h1>
		<span class=""></span>
		
		<form class="editCall" method="POST" action="/calls/<?=$currentCall["id"];?>/edit">
			<div class="form-group">
				<label for="AN">Номер клиента</label>
				<input type="text" readonly class="form-control" id="AN" value="<?=$currentCall["AN"];?>">
			</div>
			<div class="form-group">
				<label for="AutoNumber">Номер автомата</label>
				<input type="text" class="form-control" name="AutoNumber" id="AutoNumber" value="<?=$currentCall["AutoNumber"];?>">
			</div>
			<div class="form-group">
				<label for="PointName">Название точки</label>
				<input type="text" class="form-control" name="PointName" id="PointName" value="<?=$currentCall["PointName"];?>">
			</div>
			<div class="form-group">
				<label for="PetitionReason">Причина обращения</label>
				<a class="btn btn-primary photoconfirm" href="/sms/photoconfirm" role="button">Отправить смс о запросе<br>фотоподтверждения</a>
				<div id="photoconfirm"></div>
				<input type="text" class="form-control" name="PetitionReason" id="PetitionReason" value="<?=$currentCall["PetitionReason"];?>">
			</div>
			<div class="form-group">
				<label for="Compensation">Компенсация</label>
				<a class="btn btn-primary compensation" href="#" role="button">Отправить смс клиенту<br>о компенсации</a>
				<div id="compensation"></div>
				<input type="number" class="form-control" name="Compensation" id="Compensation" value="<?=$currentCall["Compensation"];?>">
			</div>
			<div class="form-group">
				<label for="Responsible">Ответственный за выполнение</label>
				<a class="btn btn-primary responsible" href="#" role="button">Отправить смс ответственному<br>о задаче</a>
				<div id="responsible"></div>
				<select id="Responsible" name="Responsible" class="form-control" required>
					<?php foreach($users as $user): ?>
						<?php if($user["username"] == $currentCall["Responsible"]): ?>
							<option value="<?=$user["id"];?>" selected><?=$user["username"];?></option>
						<?php else: ?>
							<option value="<?=$user["id"];?>"><?=$user["username"];?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="Deadline">Дедлайн</label>
				<input type="text" class="form-control" name="Deadline" value="<?=$currentCall["Deadline"];?>" placeholder="дд.мм.гггг" id="datetimepicker_one"> 
			</div>
			<div class="form-group">
				<label for="isDone">Задача выполнена</label>
				<a class="btn btn-primary taskcompletion" href="#" role="button">Отправить смс клиенту<br>о завершении задачи</a>
				<div id="taskcompletion"></div>
				<select name="isDone" id="isDone" class="form-control" required>
					<option <?php if($currentCall['isDone'] == 0): ?> selected<?php endif; ?> value="0">Нет</option>
					<option <?php if($currentCall['isDone'] == 1): ?> selected<?php endif; ?> value="1">Да</option>
				</select>
			</div>
			<div class="form-group">
				<label for="Comments">Комментарии</label>
				<textarea class="form-control" id="Comments" name="Comments"><?=$currentCall["Comments"];?></textarea>
			</div>
			<input type="hidden" name="AN" value="<?=$currentCall["AN"];?>">
			<input type="hidden" name="call_id" value="<?=$currentCall["id"];?>">
			<input type="hidden" name="EventTime" value="<?=$currentCall["EventTime"];?>">
			<input type="hidden" name="whoEdit" value="<?=$_SESSION['user']['username'];?>">
			<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить</button>
		</form>
    </div>
	
</main>

	<script>
		$('#datetimepicker_one').datetimepicker({
			format:'d.m.Y H:i',
			startDate:'+1971.05.01',
			lang:'ru'
		});
	</script>
