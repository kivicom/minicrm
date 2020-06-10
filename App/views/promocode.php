<?php $this->layout('layout/layout', ['title' => 'Обрабатываем промокод', 'auth' => $auth, 'activate' => $activate, 'msg' => $msg, 'appeal' => $appeal, 'url' => $url]);?>
<main class="py-4">
    <div class="container">
        <div id="result"><p><?php echo $msg;?></p>

            <?php if($activate):?>
            <form id="form" action="<?php echo $this->e($url);?>" method="POST">
                <input type="hidden" name="num_automat" value="<?php echo $this->e($appeal['num_automat']);?>">
                <input type="hidden" name="sum_promo" value="<?php echo $this->e($appeal['sum_promo']);?>">
                <input type="hidden" name="phone_of_client" value="<?php echo $this->e($appeal['phone_of_client']);?>">
                <input type="hidden" name="rand_gen" value="<?php echo $this->e($appeal['rand_gen']);?>">
                <button type="submit" class="btn btn-primary">Начислить</button>
            </form>
            <?php endif;?>
        </div>
    </div>
</main>