<?php $this->layout('layout/layout', ['title' => 'Редактирование обращения', 'auth' => $auth, 'appeal' => $appeal]); ?>
<main class="py-4">
    <div class="container">

        <div class="modal-body">

            <form action="/edit/<?php echo $appeal['id'];?>" method="POST">
                <input type="hidden" class="form-control" name="user_id" id="inputAuthor" value="<?php echo $_SESSION['auth_user_id'];?>">
                <div class="form-group">
                    <label for="inputTel">Телефон клиента</label>
                    <input type="text" class="phone_number form-control" name="phone_of_client" id="inputTel" placeholder="79999999999"  value="<?php echo $appeal['phone_of_client'];?>" required>
                    <small id="telHelp" class="form-text text-muted">Номер телефона в формате 79999999999</small>
                </div>
                <div class="form-group">
                    <label for="inputNumAutomat">Номер автомата</label>
                    <input type="text" class="form-control" name="num_automat" id="inputNumAutomat" placeholder="" value="<?php echo $appeal['num_automat'];?>" required>
                </div>
                <div class="form-group">
                    <label for="inputSumPromo">Сумма промокода</label>
                    <input type="number" min="0" max="200" class="form-control" name="sum_promo" id="inputSumPromo" placeholder="максимум 200" value="<?php echo $appeal['sum_promo'];?>" required>
                </div>
                <div class="form-group">
                    <label for="inputNotice">Причина создания</label>
                    <input type="text" class="form-control" name="notice" id="inputNotice" placeholder="" value="<?php echo $appeal['notice'];?>" required>
                </div>
                <div class="form-group">
                    <label for="inputExpired">Срок дейтсивия кода</label>
                    <input type="text" class="form-control" name="expired" id="inputExpired" placeholder="" value="<?php echo $appeal['expired'];?>" required>
                </div>
                <div class="form-group">
                    <label for="inputRandGen">Сгенерированный код</label>
                    <input type="text" class="form-control" name="rand_gen" id="inputRandGen" placeholder="" value="<?php echo $appeal['rand_gen']; ?>" readonly required>
                </div>
                <div id="result" class="mt-3 mb-3"></div>
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                <button type="submit" id="sendsms" class="btn btn-warning">Отправить смс сообщение повторно</button>
            </form>



        </div>
    </div>
</main>