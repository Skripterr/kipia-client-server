<div class="container padding">
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="box box-header" style="min-width:350px">
            <form id="login-form">
                <h2 class="my-2 text-center">Aвторизация</h2>
                <div class="form-group">
                    <label>Ваш логин</label>
                    <input name="username" type="text" id="username" class="form-control" placeholder="Логин" required="" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Ваш пароль</label>
                    <input name="password" type="password" id="password" class="form-control" placeholder="Пароль" required="">
                </div>
                <div class="form-group mt-4">
                    <button class="btn btn-block btn-fw white" type="submit" name="submit" id="submit"><i class="fas fa-sign-in-alt"></i> Войти</button>
                </div>
            </form>
            <div class="text-center mt-4 mb-0">Используйте данные, выданные во время стажировки.</div>
        </div>
    </div>
</div>
<script>
    $('form').on('submit', function() {
        Application.userLogin();
        return false;
    })
</script>