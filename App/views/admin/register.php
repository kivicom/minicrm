<?php $this->layout('layout/admin', ['title' => 'Добавить администратора','auth' => $auth]); ?>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Добавить администратора</div>

                        <div class="card-body">
                            <?php echo flash()->display();?>
                            <form method="POST" action="">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="username" autofocus>
                                        <?php if(isset($_SESSION['error']['username'])):?>
                                            <span class="text-danger">
                                            <?php echo $_SESSION['error']['username']; unset($_SESSION['error']['username']);?>
                                        </span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" >
                                        <?php if(isset($_SESSION['error']['email'])):?>
                                            <span class="text-danger">
                                                <?php echo $_SESSION['error']['email']; unset($_SESSION['error']['email']);?>
                                            </span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control " name="password"  autocomplete="new-password">
                                        <?php if(isset($_SESSION['error']['password'])):?>
                                            <span class="text-danger">
                                            <?php echo $_SESSION['error']['password']; unset($_SESSION['error']['password']);?>
                                        </span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Подтвердите пароль</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                        <?php if(isset($_SESSION['error']['password_confirmation'])):?>
                                            <span class="text-danger">
                                            <?php echo $_SESSION['error']['password_confirmation']; unset($_SESSION['error']['password_confirmation']);?>
                                        </span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
