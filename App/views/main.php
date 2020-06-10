<?php $this->layout('layout/layout', ['title' => 'Главная', 'auth' => $auth, 'appeals' => $appeals, 'gen_key' => $gen_key]); ?>

<main class="py-4">
    <div class="container">
        <?php echo flash()->display();?>
        <p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appealModal">
                Создать промокод
            </button></p>
        <div id="result"></div>
        <table id="appeal" class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>
                    <a href="orderby=created_at&dir=asc" title="По возрастанию">▲</a>
                    <span>Создано</span>
                    <a href="orderby=created_at&dir=desc" title="По убыванию">▼</a>
                </th>
                <th>
                    <a href="orderby=username&dir=asc" title="По возрастанию">▲</a>
                    <span>Автор</span>
                    <a href="orderby=username&dir=desc" title="По убыванию">▼</a>
                </th>
                <th><span>Номер клиента</span></th>
                <th><span>Номер автомата</span></th>
                <th><span>Сумма промокода</span></th>
                <th><span>Причина</span></th>
                <th>
                    <a href="orderby=expired&dir=asc" title="По возрастанию">▲</a>
                    <span>Срок дейтсивия</span>
                    <a href="orderby=expired&dir=desc" title="По убыванию">▼</a>
                </th>
                <th><span>Код</span></th>
                <th><span>Статус</span></th>
                <th><span>Удалить</span></th>
            </tr>
            </thead>
            <tbody>

                <?php if($appeals):?>
                    <?php foreach ($appeals as $i => $appeal):?>
						<?php
                            if(strtotime($appeal['expired']) < strtotime('NOW')){
                                $class = "expired";
                            }
                            if(strtotime($appeal['expired']) > strtotime('NOW')){
                                $class = "not_used";
                            }
                            if((strtotime($appeal['used_promo']) > 0) && (strtotime($appeal['expired']) < strtotime('NOW'))){
                                $class = "used";
                            } 
                        ?>
                        <tr>
                            <th scope="row"><a href="/edit/<?php echo $appeal['a_id'];?>" data-id="<?php echo $appeal['a_id'];?>"><?php echo $i+1 ;?><a></th>
                            <td><?php echo $appeal['created_at'];?></td>
                            <td><?php echo $appeal['username'];?></td>
                            <td><span class="tel_client"><?php echo $appeal['phone_of_client'];?></span></td>
                            <td><?php echo $appeal['num_automat'];?></td>
                            <td><?php echo $appeal['sum_promo'];?></td>
                            <td><?php echo $appeal['notice'];?></td>
                            <td><?php echo $appeal['expired'];?></td>
                            <td><?php echo $appeal['rand_gen'];?></td>
                            <td>
                                <span class="<?php echo $class;?>">
                                    <?php echo $appeal['appeal_status'];?>
                                </span>
                                <?php echo (strtotime($appeal['used_promo']) > 0) ? $appeal['used_promo'] : "";?>
                            </td>
                            <td><a href="/delete/<?php echo $appeal['a_id'];?>" data-id="<?php echo $appeal['a_id'];?>" type="button" class="btn btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="appealModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавление обращения</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <input type="hidden" class="form-control" name="user_id" id="inputAuthor" value="<?php echo $_SESSION['auth_user_id'];?>">
                            <div class="form-group">
                                <label for="inputTel">Телефон клиента</label>
                                <input type="text" class="phone_number form-control" name="phone_of_client" id="inputTel" placeholder="79999999999" required>
                                <small id="telHelp" class="form-text text-muted">Номер телефона в формате 79999999999</small>
                            </div>
                            <div class="form-group">
                                <label for="inputNumAutomat">Номер автомата</label>
                                <input type="text" class="form-control" name="num_automat" id="inputNumAutomat" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="inputSumPromo">Сумма промокода</label>
                                <input type="number" min="0" max="200" class="form-control" name="sum_promo" id="inputSumPromo" placeholder="максимум 200" required>
                            </div>
                            <div class="form-group">
                                <label for="inputNotice">Причина создания</label>
                                <input type="text" class="form-control" name="notice" id="inputNotice" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="inputExpired">Срок дейтсивия кода</label>
                                <input type="text" class="form-control" name="expired" id="inputExpired" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="inputRandGen">Сгенерированный код</label>
                                <input type="text" class="form-control" name="rand_gen" id="inputRandGen" placeholder="" value="<?php echo $gen_key; ?>" readonly required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cоздать промокод</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>
</main>
