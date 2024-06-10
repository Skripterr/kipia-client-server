<div class="container padding">
    <div class="row align-items-stretch">
        <div class="col-sm-12 d-flex align-items-stretch">
            <div class="box w-100">
                <div class="box-header">
                    <div class="row mb-3">
                        <div class="col">
                            <h3 class="float-left">
                                <span class="text-muted">Изделия</span>
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
                                    <th>Название</th>
                                    <th>Вес, грамм</th>
                                    <th>Стоимость, ₽</th>
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
                    <h5 class="modal-title">Добавление изделия</h5>
                </div>
                <form id="add-modal-form">
                    <div class="modal-body p-lg">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Название</label>
                            <div class="input-group">
                                <input name="name" id="name" class="form-control" placeholder="Название изделия" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Вес</label>
                            <div class="input-group">
                                <input name="weight" id="weight" class="form-control" placeholder="Вес изделия" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Цена</label>
                            <div class="input-group">
                                <input name="pricing" id="pricing" class="form-control" placeholder="Цена изделия" type="text" required="">
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
                <h5 class="modal-title">Настройки изделия</h5>
            </div>
            <div class="modal-body p-lg">
                <form id="edit-modal-form">
                    <div class="modal-body p-lg">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Название</label>
                            <div class="input-group">
                                <input name="name" id="name" class="form-control" placeholder="Название изделия" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Вес</label>
                            <div class="input-group">
                                <input name="weight" id="weight" class="form-control" placeholder="Вес изделия" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Цена</label>
                            <div class="input-group">
                                <input name="pricing" id="pricing" class="form-control" placeholder="Цена изделия" type="text" required="">
                            </div>
                        </div>
                </form>
                <div class="form-group col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-block btn-outline b-primary text-primary p-x-md printItemButton"><i class="fa fa-print" aria-hidden="true"></i> Ингредиенты</button>
                </div>
                <div class="form-group col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-block btn-outline b-danger text-danger p-x-md deleteButton">Удалить изделие</button>
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
        Application.bakingGet();
    });

    $(".addButton").click(function() {
        $('#modal-add').modal('show');
    });


    $(".deleteButton").on('click', function() {
        Application.bakingDelete($('#edit-modal-form').data('baking')['id']);
    });

    $(".saveButton").on('click', function() {
        Application.bakingEdit($('#edit-modal-form').data('baking')['id']);
    });

    $(".printItemButton").on('click', function() {
        let baking = $('#edit-modal-form').data('baking');
        Application.request('ingredients', 'get', data = {'baking_id': baking.id}).success((response) => {
            Printer.createWindow(Printer.generateIngredientsReport(baking, response.data));
        });
    });

    $('#add-modal-form').on('submit', function() {
        Application.bakingAdd();
        return false;
    });

    $(document).on('hidden.bs.modal', function() {
        $("input").val("").change();
    });

    $(document).on('click', ".manageButton", function() {
        const baking = $(this).closest('tr').data('baking');
        $('#edit-modal-form input[name="name"]').val(baking.name);
        $('#edit-modal-form input[name="weight"]').val(baking.weight);
        $('#edit-modal-form input[name="pricing"]').val(baking.pricing);
        $('#edit-modal-form').data('baking', baking);
        $('#modal-edit').modal('show');
    });
</script>