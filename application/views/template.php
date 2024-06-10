<!DOCTYPE HTML>

<html style="background-color: #1e2835">

<head>

    <title><?= $title ?> | <?= Application\Core\Config::PROJECT_TITLE ?></title>

    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta property="og:title" content="<?= Application\Core\Config::PROJECT_TITLE ?>" />
    <meta name="theme-color" content="#1e2835" />

    <link rel="icon" href="/public/images/logo.ico?v=<?= $frontCacheControl ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link rel="stylesheet" href="/public/css/app.min.css?v=<?= $frontCacheControl ?>">
    <link rel="stylesheet" href="/public/css/primary.css?v=<?= $frontCacheControl ?>">
    <link rel="stylesheet" href="/public/css/app.min.css?v=<?= $frontCacheControl ?>">
    <link rel="stylesheet" href="/public/css/styles.css?v=<?= $frontCacheControl ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="/public/js/izitoast.js?v=<?= $frontCacheControl ?>"></script>
    <script src="/public/js/global.js?v=<?= $frontCacheControl ?>"></script>
    <script src="/public/js/application.js?v=<?= $frontCacheControl ?>"></script>
    <script src="/public/js/print.js?v=<?= $frontCacheControl ?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            $('.content').delay(500).fadeIn();
            Application.init();
            if (document.location.pathname !== '/authorization') {
                Application.usersMe();
            }
        });
    </script>

</head>

<body class="dark">

    <div class="content">
        <div class="content-header white box-shadow-0">
            <div class="navbar navbar-expand-lg">
                <div class="navbar-text nav-title flex">
                    <a class="navbar-brand font-weight-bold" href="/">
                        <img src="/public/images/logotype.png" width="35" height="30" class="mr-1 mb-1" alt="">
                        <?= Application\Core\Config::PROJECT_TITLE ?>
                    </a>
                </div>
                <?php if ((new Application\Core\Utils\Cookies)->existCookie('access_token')) { ?>
                    <ul class="nav flex-row order-lg-2">
                        <li class="nav-item dropdown">
                            <a class="d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="avatar w-32"><img src="/public/images/empty.png" alt="User Icon"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dark animate fadeIn" aria-labelledby="navbarDropdown">
                                <div class="dropdown-item" id="userDataInitials"></div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/users">Пользователи</a>
                                <a class="dropdown-item" href="/branches">Филиалы</a>
                                <a class="dropdown-item" href="/baking">Изделия</a>
                                <a class="dropdown-item" href="/ingredients">Ингредиенты</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item modalLogoutButton" href="/logout">Выйти</a>
                            </div>
                        </li>
                    </ul>
                    <div id="modal-logout" class="modal black-overlay fade" data-backdrop="false" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content dark lt">
                                <div class="modal-header">
                                    <h5 class="modal-title">Подтвердите действие</h5>
                                </div>
                                <div class="modal-body p-lg">
                                    <div class="mt-2 text-center">
                                        <h4 class="my-2">Вы уверены, что хотите выйти?</h4>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn white p-x-md modalDismissButton" data-dismiss="modal">Отмена</button>
                                    <button type="submit" class="btn btn-outline b-primary text-primary p-x-md modalConfirmLogoutButton">Подтвердить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(".modalLogoutButton").on('click', function() {
                            $('#modal-logout').modal('show');
                            return false;
                        });

                        $(".modalConfirmLogoutButton").on('click', function() {
                            Application.userLogout();
                            return false;
                        });
                    </script>
                <?php } ?>
            </div>
        </div>
        <div class="loader-block">
            <div class="loader" style="visibility: hidden">
                <div class="bar"></div>
            </div>
        </div>
        <div class="body-content">
            <?php include $content ?>
        </div>
    </div>

</body>

</html>