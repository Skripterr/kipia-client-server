<div class="container padding">
    <div class="row align-items-stretch">
        <div class="col-sm-12 d-flex align-items-stretch">
            <div class="box w-100">
                <div class="box-header">
                    <div class="row mb-3">
                        <div class="col">
                            <h3 class="float-left">
                                <span class="text-muted">Филиалы</span>
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
                                    <th>Адрес фалиала</th>
                                    <th>Статус</th>
                                    <th>Управление</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-add" class="modal black-overlay fade" data-backdrop="false" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark lt">
                <div class="modal-header">
                    <h5 class="modal-title">Добавление филиала</h5>
                </div>
                <form id="add-modal-form">
                    <div class="modal-body p-lg">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Адрес филиала</label>
                            <div class="input-group">
                                <input name="address" id="address" class="form-control" placeholder="Адрес" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Статус</label>
                            <div class="input-group">
                                <select name="status" class="form-control" id="status-selection" required=""></select>
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
</div>

<div id="modal-edit" class="modal black-overlay fade" data-backdrop="false" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content dark lt">
            <div class="modal-header">
                <h5 class="modal-title">Настройки филиала</h5>
            </div>
            <div class="modal-body p-lg">
                <form id="edit-modal-form">
                    <div class="modal-body p-lg">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Адрес филиала</label>
                            <div class="input-group">
                                <input name="address" id="address" class="form-control" placeholder="Адрес" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Статус</label>
                            <div class="input-group">
                                <select name="status" class="form-control" id="edit-status-selection" required=""></select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="form-group col-sm-12 col-md-12">
                    <div class="row">
                        <div class="col">
                        <button type="submit" class="btn btn-block btn-outline b-primary text-primary p-x-md printUserButton">
                        <i class="fa fa-print" aria-hidden="true"></i> Персонал
                    </button>
                        </div>
                        <div class="col">
                        <button type="submit" class="btn btn-block btn-outline b-primary text-primary p-x-md printBakingButton">
                        <i class="fa fa-print" aria-hidden="true"></i> Изделия
                        </button>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-block btn-outline b-danger text-danger p-x-md deleteButton">Удалить филиал</button>
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
        Application.branchesGet();

        $.each(Application.g_BranchStatuses, function(i, tag) {
            $('#status-selection, #edit-status-selection').append($('<option>', {
                value: i,
                text: tag
            }))
        });
    });

    $(".addButton").click(function() {
        $('#modal-add').modal('show');
    });

    $(".deleteButton").on('click', function() {
        Application.branchesDelete($('#edit-modal-form').data('branch')['id']);
    });

    $(".saveButton").on('click', function() {
        Application.branchesEdit($('#edit-modal-form').data('branch')['id']);
    });

    $(".printBakingButton").on('click', function() {
        Application.request('baking', 'get').success((response) => {
            let branch = $('#edit-modal-form').data('branch');
            branch.status = Application.g_BranchStatuses[branch.status];
            Printer.createWindow(Printer.generateBakingReport(branch, response.data));
        });
    });

    $(".printUserButton").on('click', function() {
        Application.request('users', 'get', data = {
            'branch': $('#edit-modal-form').data('branch')['branch']
        }).success((response) => {
            let users = response.data.map((user) => {
                return {
                    ...user,
                    role: Application.g_UserStatuses[user.role],
                };
            });
            let branch = $('#edit-modal-form').data('branch');
            branch.status = Application.g_BranchStatuses[branch.status];
            Printer.createWindow(Printer.generateUserReport(branch, users));
        });
    });

    $('#add-modal-form').on('submit', function() {
        Application.branchesAdd();
        return false;
    });

    $(document).on('hidden.bs.modal', function() {
        $("input").val("").change();
    });

    $(document).on('click', ".manageButton", function() {
        const branch = $(this).closest('tr').data('branch');
        $('#edit-modal-form input[name="address"]').val(branch.address);
        $('#edit-modal-form select[name="status"]').val(branch.status);
        $('#edit-modal-form').data('branch', branch);
        $('#modal-edit').modal('show');
    });
</script>