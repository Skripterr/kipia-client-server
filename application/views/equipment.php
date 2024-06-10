<div class="container padding">
    <div class="row align-items-stretch">
        <div class="col-sm-12 d-flex align-items-stretch">
            <div class="box w-100">
                <div class="box-header">
                    <div class="row mb-3">
                        <div class="col">
                            <h3 class="float-left">
                                <span class="text-muted">Оборудование</span>
                            </h3>
                        </div>
                        <div class="col">
                            <form id="select-accounts-form" class="form-inline float-right">
                                <select name="branch" class="form-control form-control-sm mr-1" id="table-branch-selection">
                                    <option value="-1">Не выбран филиал</option>
                                </select>
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
                                    <th>Интервал сан. обработки</th>
                                    <th>Последняя сан. обработка</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Выберите филиал.</td>
                                </tr>
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
                    <h5 class="modal-title">Добавление оборудования</h5>
                </div>
                <form id="add-modal-form">
                    <div class="modal-body p-lg">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Название оборудования</label>
                            <div class="input-group">
                                <input name="name" id="name" class="form-control" placeholder="Название ингредиента" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Интервал сан. обработки</label>
                            <div class="input-group">
                                <select name="sanitizing_interval" class="form-control" id="sanitizing-interval-selection">
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Последняя сан. обработка</label>
                            <div class="input-group">
                                <input name="last_sanitizing_date" id="last-sanitizing-date" class="form-control" placeholder="Дата последней сан. обработки" type="datetime-local" required="">
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
                <h5 class="modal-title">Настройки ингредиента</h5>
            </div>
            <div class="modal-body p-lg">
                <form id="edit-modal-form">
                    <div class="modal-body p-lg">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Название оборудования</label>
                            <div class="input-group">
                                <input name="name" id="name" class="form-control" placeholder="Название ингредиента" type="text" required="">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Интервал сан. обработки</label>
                            <div class="input-group">
                                <select name="sanitizing_interval" class="form-control" id="sanitizing-interval-selection">
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Последняя сан. обработка</label>
                            <div class="input-group">
                                <input name="last_sanitizing_date" id="last-sanitizing-date" class="form-control" placeholder="Дата последней сан. обработки" type="datetime-local" required="">
                            </div>
                        </div>
                </form>
                <div class="form-group col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-block btn-outline b-danger text-danger p-x-md deleteButton">Удалить оборудование</button>
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
                    $('#branches-selection, #table-branches-selection').append($('<option>', {
                        value: element['id'],
                        text: element['name']
                    }))
                });
            }
        });
    });

    $('#table-baking-selection').on('change', function() {
        Application.ingredientsGet(this.value);
    });

    $(".addButton").click(function() {
        $('#modal-add').modal('show');
    });

    $(".deleteButton").on('click', function() {
        Application.ingredientsDelete($('#edit-modal-form').data('ingredients')['id']);
    });

    $(".saveButton").on('click', function() {
        Application.ingredientsEdit($('#edit-modal-form').data('ingredients')['id']);
    });

    $('#add-modal-form').on('submit', function() {
        Application.ingredientsAdd();
        return false;
    });

    $(document).on('hidden.bs.modal', function() {
        $("input").val("").change();
    });

    $(document).on('click', ".manageButton", function() {
        const ingredients = $(this).closest('tr').data('ingredients');
        $('#edit-modal-form input[name="name"]').val(ingredients.name);
        $('#edit-modal-form input[name="weight"]').val(ingredients.weight);
        $('#edit-modal-form').data('ingredients', ingredients);
        $('#modal-edit').modal('show');
    });
</script>