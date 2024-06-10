<div class="container padding">
    <div class="row align-items-stretch">
        <div class="col-sm-12 d-flex align-items-stretch">
            <div class="box w-100">
                <div class="box-header">
                    <div class="row mb-3">
                        <div class="col">
                            <h3 class="float-left">
                                <span class="text-muted">Пользователи</span>
                            </h3>
                        </div>
                        <div class="col">
                            <form id="select-accounts-form" class="form-inline float-right">
                                <a class="btn btn-sm white addButton"><i class="fa fa-plus"></i></a>
                            </form>
                        </div>

                    </div>
                    <div class="table-responsive border border-dark">
                        <table class="table text-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Фамилия</th>
                                    <th>Отчество</th>
                                    <th>Имя</th>
                                    <th>Должность</th>
                                    <th>Филиал</th>
                                    <th>Управление</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <nav class="paginationScroll float-right d-none mt-3">
                        <ul class="pagination">
                            <li class="page-item scrollLeftButton"><a class="page-link"><i class="fas fa-chevron-left"></i></a></li>
                            <li class="page-item scrollRightButton"><a class="page-link"><i class="fas fa-chevron-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-add" class="modal black-overlay fade" data-backdrop="false" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark lt">
                <div class="modal-header">
                    <h5 class="modal-title">Добавление пользователя</h5>
                </div>
                <form id="add-modal-form">
                    <div class="modal-body p-lg">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Username</label>
                            <div class="input-group">
                                <input name="username" id="username" class="form-control" placeholder="Username" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Имя</label>
                            <div class="input-group">
                                <input name="first_name" id="firstName" class="form-control" placeholder="Имя" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Отчество</label>
                            <div class="input-group">
                                <input name="middle_name" id="middleName" class="form-control" placeholder="Отчество" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Фамилия</label>
                            <div class="input-group">
                                <input name="last_name" id="lastName" class="form-control" placeholder="Фамилия" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Должность</label>
                            <div class="input-group">
                                <select name="role" class="form-control" id="role-selection" required=""></select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Филиал</label>
                            <div class="input-group">
                                <select name="branch" class="form-control" id="branch-selection" required=""></select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Пароль</label>
                            <div class="input-group">
                                <input name="password" id="password" class="form-control" placeholder="Пароль" type="password" required="">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <small class="text-muted">Пользователь добавляется только после полного инструктажа.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn white p-x-md modalDismissButton" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-outline b-primary text-primary p-x-md">Подтвердить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-edit" class="modal black-overlay fade" data-backdrop="false" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark lt">
                <div class="modal-header">
                    <h5 class="modal-title">Настройки пользователя</h5>
                </div>
                <div class="modal-body p-lg">
                    <form id="edit-modal-form">
                        <div class="modal-body p-lg">
                            <div class="form-group col-sm-12 col-md-12">
                                <label>Username</label>
                                <div class="input-group">
                                    <input name="username" id="edit-username" class="form-control" placeholder="Username" type="text" required="">
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label>Имя</label>
                                <div class="input-group">
                                    <input name="first_name" id="edit-firstName" class="form-control" placeholder="Имя" type="text" required="">
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label>Отчество</label>
                                <div class="input-group">
                                    <input name="middle_name" id="edit-middleName" class="form-control" placeholder="Отчество" type="text" required="">
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label>Фамилия</label>
                                <div class="input-group">
                                    <input name="last_name" id="edit-lastName" class="form-control" placeholder="Фамилия" type="text" required="">
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label>Должность</label>
                                <div class="input-group">
                                    <select name="role" class="form-control" id="edit-role-selection" required=""></select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label>Филиал</label>
                                <div class="input-group">
                                    <select name="branch" class="form-control" id="edit-branch-selection" required=""></select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="form-group col-sm-12 col-md-12">
                        <button type="submit" class="btn btn-block btn-outline b-danger text-danger p-x-md deleteButton">Удалить пользователя</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn white p-x-md modalDismissButton" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-outline b-primary text-primary p-x-md saveButton">Сохранить</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).on('DOMContentLoaded', function() {
            Application.request('branches', 'get').success((response) => {
                if (response.error) {
                    showError(response.message);
                } else {
                    const $tbody = $('tbody');
                    $tbody.delay(500).fadeIn().empty();

                    response.data.forEach((element) => {
                        Application.g_BranchesData[element['id']] = element;
                    });

                    Application.usersGet();

                    $.each(Application.g_BranchesData, function(i, value) {
                        $('#branch-selection, #edit-branch-selection').append($('<option>', {
                            value: value.id,
                            text: value.address
                        }))
                    });
                }
            });

            $.each(Application.g_UserStatuses, function(i, value) {
                $('#role-selection, #edit-role-selection').append($('<option>', {
                    value: i,
                    text: value
                }))
            });
        });

        $(".addButton").click(function() {
            $('#modal-add').modal('show');
        });

        $(".deleteButton").on('click', function() {
            Application.usersDelete($('#edit-modal-form').data('account')['id']);
        });

        $(".saveButton").on('click', function() {
            Application.usersEdit($('#edit-modal-form').data('account')['id']);
        });

        $('#add-modal-form').on('submit', function() {
            Application.usersAdd();
            return false;
        });

        $(document).on('hidden.bs.modal', function() {
            $("input").val("").change();
        });

        $(document).on('click', ".manageButton", function() {
            const account = $(this).closest('tr').data('account');
            $('#edit-modal-form input[name="username"]').val(account.username);
            $('#edit-modal-form input[name="first_name"]').val(account.first_name);
            $('#edit-modal-form input[name="middle_name"]').val(account.middle_name);
            $('#edit-modal-form input[name="last_name"]').val(account.last_name);
            $('#edit-modal-form select[name="role"]').val(account.role);
            $('#edit-modal-form select[name="branch"]').val(account.branch);
            $('#edit-modal-form').data('account', account);
            $('#modal-edit').modal('show');
        });
    </script>