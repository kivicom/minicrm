<?php $this->layout('layout/layout', ['title' => 'Авторизация', 'auth' => $auth]); ?>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Авторизация</div>
                        <div class="card-body">

                            <?php echo flash()->display();?>

                            <form method="POST" action="">

                                <div class="form-group row">
                                    <label for="username" class="col-md-4 col-form-label text-md-right">Имя</label>

                                    <div class="col-md-6">
                                        <input id="email" type="username" class="form-control" name="username"  autocomplete="username" >
                                        <?php if(isset($_SESSION['error']['username'])):?>
                                            <span class="text-danger">
                                                <?php echo $_SESSION['error']['username']; unset($_SESSION['error']['username']);?>
                                            </span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password"  autocomplete="current-password">
                                        <?php if(isset($_SESSION['error']['password'])):?>
                                            <span class="text-danger">
                                                <?php echo $_SESSION['error']['password']; unset($_SESSION['email_error']);?>
                                            </span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                                            <label class="form-check-label" for="remember">
                                                Запомнить меня
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Login
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
