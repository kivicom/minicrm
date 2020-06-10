<?php $this->layout('layout/admin', ['title' => 'Список пользователей', 'auth' => $auth, 'user' => $user]); ?>
<main class="py-4">
    <div class="container">
		<?php echo flash()->display();?>
        <div class="modal-body">
			<div class="jumbotron">
				<form action="edit" method="POST">
					<input type="hidden" class="form-control" name="id" id="inputAuthor" value="<?php echo $user['id'];?>">
					<div class="form-group">
						<label for="inputName">Имя пользователя</label>
						<?php if($user['verified'] == 0):?>
							<span class="badge badge-warning">Не подтвержденый</span>
						<?php endif;?>
						<input type="text" class="username form-control" name="username" id="inputName" placeholder=""  value="<?php echo $user['username'];?>" required>
					</div>
					<div class="form-group">
						<label for="inputPhone">Телефон пользователя</label>
						<input type="text" class="phone form-control" name="phone" id="inputPhone" placeholder=""  value="<?php echo $user['phone'];?>" required>
					</div>
					<div class="form-group">
						<label for="inputNumAutomat">Статус пользователя</label>
						<select name="roles_mask" class="form-control">
							<?php if($user['roles_mask'] == 1):?>
								<option value="1">Администратор</option>
								<option value="0">Зарегистрированный</option>
							<?php else:?>
								<option value="0">Зарегистрированный</option>
								<option value="1">Администратор</option>
							<?php endif;?>
						</select>
					</div>
					
					<div id="result" class="mt-3 mb-3"></div>
					<button type="submit" class="btn btn-primary">Сохранить изменения</button>
				</form>
			</div>

			<div class="jumbotron">
				<h2>Изменить пароль</h2>
				<form action="changepass" method="POST">
					<div class="form-group">
						<label for="oldPassword">Прежний пароль</label>
						<input type="password" name="oldPassword" class="form-control" id="oldPassword" placeholder="Прежний пароль">
					</div>
					
					<div class="form-group">
						<label for="newPassword">Новый пароль</label>
						<input type="password" name="newPassword" class="form-control" id="newPassword" placeholder="Новый пароль">
					</div>
					
					<div id="result" class="mt-3 mb-3"></div>
					<button type="submit" class="btn btn-primary">Изменить пароль</button>
				</form>
			</div>

        </div>
    </div>
</main>