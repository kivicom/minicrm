<?php $this->layout('layout/admin', ['title' => 'Конфигурация API','auth' => $auth, 'api_key' => $api_key]); ?>

<main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Конфигурация API</div>

                    <div class="card-body">
                        <?php echo flash()->display();?>
                        <form method="POST" action="">

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">API</label>

                                <div class="col-md-6">
                                    <input id="key_api" type="text" class="form-control" name="key_api" value="<?php echo $api_key['key_api'];?>" autofocus>
                                    <input type="hidden" name="id" value="<?php echo $api_key['id'];?>">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Сохранить изменения
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
