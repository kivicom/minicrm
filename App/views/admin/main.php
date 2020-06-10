<?php $this->layout('layout/admin', ['title' => 'Список пользователей', 'auth' => $auth, 'users' => $users]); ?>

<main class="py-4">
    <div class="container">
        <table id="users_rolles" class="table table-bordered">
            <thead>
            <tr>
                <th>id</th>
                <th>
                    <span>Пользователь</span>
                </th>
                <th><span>Статус</span></th>
				<th><span>Удалить</span></th>
            </tr>
            </thead>
            <tbody>
            <?php if($users):?>
                <?php foreach ($users as $key => $user):?>
                    <tr>
                        <th scope="row"><?php echo $user['id'];?></th>
                        <td>
							<a href="/admin/profile/<?php echo $user['id'];?>"><?php echo $user['username'];?></a>
							<?php if($user['verified'] == 0):?>
								<span class="badge badge-warning">Не подтвержденый</span>
							<?php endif;?>
						</td>
                        <td class="rolle"><?php echo ($user['roles_mask'] == 1) ? 'Администратор': 'Зарегистрированный';?></td>
						<td>
                            <form action="/profile/delete" method="POST">
                                <input type="hidden" name="id" value="<?php echo $user['id'];?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?');">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
    </div>
</main>
