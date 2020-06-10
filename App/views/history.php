<?php $this->layout('layout/admin', ['title' => 'История всех изменений', 'auth' => $auth, 'allhistory' => $allhistory, 'users' => $users]); 

function isDone($id)
{
	switch($id)
	{
		case 0: return "Нет"; break;
		case 1: return "Да"; break;
	}
}

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<main class="py-4">

    <div class="container-fluid">
	
        <?php echo flash()->display();?>

        <div id="result">
		
		</div>
		
		<h1 class="mt-5 mb-5">История всех изменений</h1>
		
        <table id="history" class="table table-hover table-bordered">
            <thead>
            <tr>
                <th>Номер автомата</th>
                <th>Название точки</th>
                <th>Компенсация</th>
                <th>Выполнено</th>
                <th>Кто изменил</th>
                <th>Изменено</th>
            </tr>
            </thead>
            <tbody>
				<?php foreach($allhistory as $history): ?>
				<tr>
					<td><?=$history["AutoNumber"];?></td>
					<td><?=$history["PointName"];?></td>
					<td><?=$history["Compensation"];?></td>
					<td><?=isDone($history["isDone"]);?></td>
					<td><?=$history["whoEdit"];?></td>
					<td><?=date('d.m.Y - H:i:s', strtotime($history["modified"]));?></td>
				</tr>

				<?php endforeach; ?>
            </tbody>
        </table>

    </div>
	
	<script>
	$(document).ready(function() {
        $('#history').dataTable( {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
            },
			"aaSorting": [[ 1, "desc" ]],
			"pageLength": 25
        } );
    } );
	</script>
	
</main>
