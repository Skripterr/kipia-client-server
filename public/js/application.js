Application = {
    g_Initialized: false,
    g_JsonWebToken: '',

    g_BranchStatuses: {
        0: 'Закрыто',
        1: 'Открыто',
        2: 'Замороженно',
        3: 'Переработка',
    },
    g_BranchesData: {},
    g_EquipmentTimeouts: {
        1: '1 час',
        4: '4 часа',
        6: '6 часов',
        12: '12 часов',
        24: '1 день',
        48: '2 дня',
        168: '1 неделя',
    },
    g_UserStatuses: {
        0: 'Стажёр',
        1: 'Уборщица',
        2: 'Пекарь',
        3: 'Продавец',
        4: 'Директор филиала',
        5: 'Бухгалтер',
        6: 'Системный администратор',
    },

    init: function () {
        Application.g_JsonWebToken = Cookies.get('access_token') || '';
        ParsedJWT = Application.parseJsonWebToken(Application.g_JsonWebToken);

        if (!ParsedJWT && document.location.pathname !== '/authorization' && window.performance.getEntries()[0].responseStatus == 200) {
            Application.userLogout();
        }

        if (Application.g_JsonWebToken && ParsedJWT !== null && ParsedJWT.expires_at <= Math.round(new Date().getTime() / 1000)) {
            console.log('Need to refresh JsonWebToken.');
            Application.userRefreshJWT();
        }

        Application.setupAjax();
        Application.g_Initialized = true;
    },

    parseJsonWebToken: function (token) {
        try {
            return JSON.parse(atob(token.split('.')[1]));
        } catch (e) {
            return null;
        }
    },

    setupAjax: function () {
        $.ajaxSetup({
            dataType: 'json',
            beforeSend: () => {
                if (navigator.cookieEnabled && Cookies.get('__ufp')) {
                    Cookies.set('__ufp', Application.storeUserFingerPrint());
                }

                $('button').attr('disabled', 'disabled');
                if (!$('body').hasClass('modal-open'))
                    $('.loader').css('visibility', 'visible');
            },
            complete: () => {
                $('button').delay(3000).removeAttr('disabled');
                $('.loader').css('visibility', 'hidden');
            },
            error: () => {
                $('a').css('pointer-events', '');
                $('button').delay(3000).removeAttr('disabled');
                showError('Не удалось получить данные.');
            },
            success: (response) => {
                if (response.message != undefined && response.message.includes('JSON') && !response.message.includes('истёк')) {
                    Application.userRefreshJWT();
                }
            }
        });
    },

    storeUserFingerPrint: function () {
        let encoded = btoa(JSON.stringify([
            $(window).width(),
            $(window).height(),
            navigator.userAgent,
            navigator.cookieEnabled,
            navigator.language,
            navigator.webdriver
        ]))
        if (encoded.indexOf('==') != -1) {
            return 1 + encoded.replace('==', '');
        }
        return 0 + encoded;
    },

    prepareDataForRequest: function (element) {
        let elements = {};

        $(element).find('input, select').each((i, el) => {
            elements[el.name] = ($(el).attr('type') == 'checkbox' ? ($(el).is(':checked') == true ? true : false) : el.value);
        });

        return elements;
    },

    request: function (endpoint, method = '', data = {}) {
        if (Application.g_JsonWebToken) {
            data['access_token'] = Application.g_JsonWebToken;
        }

        data['locale'] = 'ru';
        return $.post('/api/' + endpoint + ((method != '') ? '/' + method : method), data = data)
    },

    userLogin: function () {
        Application.request('authorization', '', data = Application.prepareDataForRequest($('#login-form'))).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                showSuccess('Вы вошли в аккаунт.');
                Cookies.set('access_token', response.access_token, {
                    expires: 1
                });
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        });
    },

    userLogout: function () {
        Cookies.remove('access_token');
        location.reload();
    },

    userRefreshJWT: function () {
        Application.request('authorization', 'refresh').success((response) => {
            if (response.error) {
                showError(response.message);
                Application.userLogout();
            } else {
                Cookies.set('access_token', response.access_token, {
                    expires: 1
                });
                Application.g_JsonWebToken = response.access_token;
                console.log('Received new JsonWebToken:' + response.access_token);
                location.reload();
            }
        });
    },

    usersMe: function () {
        Application.request('users', 'me').success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $('#userDataInitials').text(response.data.first_name + ' ' + response.data.middle_name + ' ' + response.data.last_name);
            }
        });
    },

    usersGet: function () {
        Application.request('users', 'get').success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                const $tbody = $('tbody');
                $tbody.delay(500).fadeIn().empty();

                response.data.forEach((element) => {
                    const $row = $('<tr>').attr('data-account', JSON.stringify(element));

                    const addCell = (text) => {
                        const $cell = $('<td>').text(text);
                        $row.append($cell);
                    };

                    const addHeadingCell = (text) => {
                        const $cell = $('<td>').html('<h6>' + text + '</h6>');
                        $row.append($cell);
                    };

                    addCell('#' + element['id']);
                    addHeadingCell(element['username']);
                    addHeadingCell(element['last_name']);
                    addHeadingCell(element['middle_name']);
                    addHeadingCell(element['first_name']);
                    addHeadingCell(Application.g_UserStatuses[element['role']]);
                    addCell(Application.g_BranchesData[element['branch']]['address']);

                    const $manageButton = $('<a class="btn btn-fw white manageButton">').text('Управление');
                    const $manageCell = $('<td>').append($manageButton);
                    $row.append($manageCell);

                    $tbody.append($row).hide().delay(500).fadeIn();
                });
            }
        });
    },
    usersAdd: function () {
        Application.request('users', 'add', data = Application.prepareDataForRequest($('#add-modal-form'))).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-add").modal("hide");
                showSuccess('Пользователь успешно добавлен!');
                Application.usersGet();
            }
        });
    },
    usersEdit: function (id) {
        let data = Application.prepareDataForRequest($('#edit-modal-form'));
        data['id'] = id;
        Application.request('users', 'edit', data = data).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Пользователь успешно изменен!');
                Application.usersGet();
            }
        });
    },
    usersDelete: function (id) { 
        Application.request('users', 'delete', data = {'id': id}).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Пользователь успешно удален!');
                Application.usersGet();
            }
        });
    },


    branchesGet: function () {
        Application.request('branches', 'get').success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                const $tbody = $('tbody');
                $tbody.delay(500).fadeIn().empty();

                response.data.forEach((element) => {
                    const $row = $('<tr>').attr('data-branch', JSON.stringify(element));

                    const addCell = (text) => {
                        const $cell = $('<td>').text(text);
                        $row.append($cell);
                    };

                    const addHeadingCell = (text) => {
                        const $cell = $('<td>').html('<h6>' + text + '</h6>');
                        $row.append($cell);
                    };

                    addCell('#' + element['id']);
                    addHeadingCell(element['address']);
                    addHeadingCell(Application.g_BranchStatuses[element['status']]);

                    const $manageButton = $('<a class="btn btn-fw white manageButton">').text('Управление');
                    const $manageCell = $('<td>').append($manageButton);
                    $row.append($manageCell);

                    $tbody.append($row).hide().delay(500).fadeIn();
                });
            }
        });
    },
    branchesAdd: function () {
        Application.request('branches', 'add', data = Application.prepareDataForRequest($('#add-modal-form'))).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-add").modal("hide");
                showSuccess('Филиал успешно добавлен!');
                Application.branchesGet();
            }
        });
    },
    branchesEdit: function (id) {
        let data = Application.prepareDataForRequest($('#edit-modal-form'));
        data['id'] = id;
        Application.request('branches', 'edit', data = data).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Филиал успешно изменен!');
                Application.branchesGet();
            }
        });
    },
    branchesDelete: function (id) { 
        Application.request('branches', 'delete', data = {'id': id}).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Филиал успешно удален!');
                Application.branchesGet();
            }
        });
    },


    bakingGet: function () {
        Application.request('baking', 'get').success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                const $tbody = $('tbody');
                $tbody.delay(500).fadeIn().empty();

                response.data.forEach((element) => {
                    const $row = $('<tr>').attr('data-baking', JSON.stringify(element));

                    const addCell = (text) => {
                        const $cell = $('<td>').text(text);
                        $row.append($cell);
                    };

                    const addHeadingCell = (text) => {
                        const $cell = $('<td>').html('<h6>' + text + '</h6>');
                        $row.append($cell);
                    };

                    addCell('#' + element['id']);
                    addHeadingCell(element['name']);
                    addHeadingCell(element['weight']);
                    addHeadingCell(element['pricing']);

                    const $manageButton = $('<a class="btn btn-fw white manageButton">').text('Управление');
                    const $manageCell = $('<td>').append($manageButton);
                    $row.append($manageCell);

                    $tbody.append($row).hide().delay(500).fadeIn();
                });
            }
        });
    },
    bakingAdd: function () {
        Application.request('baking', 'add', data = Application.prepareDataForRequest($('#add-modal-form'))).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-add").modal("hide");
                showSuccess('Изделие успешно добавлено!');
                Application.bakingGet();
            }
        });
    },
    bakingEdit: function (id) {
        let data = Application.prepareDataForRequest($('#edit-modal-form'));
        data['id'] = id;
        Application.request('baking', 'edit', data = data).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Изделие успешно изменено!');
                Application.bakingGet();
            }
        });
    },
    bakingDelete: function (id) { 
        Application.request('baking', 'delete', data = {'id': id}).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Изделие успешно удалено!');
                Application.bakingGet();
            }
        });
    },


    ingredientsGet: function (bakingId) {
        Application.request('ingredients', 'get', data = {'baking_id': bakingId}).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                const $tbody = $('tbody');
                $tbody.delay(500).fadeIn().empty();

                response.data.forEach((element) => {
                    const $row = $('<tr>').attr('data-ingredients', JSON.stringify(element));

                    const addCell = (text) => {
                        const $cell = $('<td>').text(text);
                        $row.append($cell);
                    };

                    const addHeadingCell = (text) => {
                        const $cell = $('<td>').html('<h6>' + text + '</h6>');
                        $row.append($cell);
                    };

                    addCell('#' + element['id']);
                    addHeadingCell(element['name']);
                    addHeadingCell(element['weight']);

                    const $manageButton = $('<a class="btn btn-fw white manageButton">').text('Управление');
                    const $manageCell = $('<td>').append($manageButton);
                    $row.append($manageCell);

                    $tbody.append($row).hide().delay(500).fadeIn();
                });
            }
        });
    },
    ingredientsAdd: function () {
        Application.request('ingredients', 'add', data = Application.prepareDataForRequest($('#add-modal-form'))).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-add").modal("hide");
                showSuccess('Ингредиент успешно добавлен!');
                Application.ingredientsGet($('#table-baking-selection').val());
            }
        });
    },
    ingredientsEdit: function (id) {
        let data = Application.prepareDataForRequest($('#edit-modal-form'));
        data['id'] = id;
        Application.request('ingredients', 'edit', data = data).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Изделие успешно изменено!');
                Application.ingredientsGet($('#table-baking-selection').val());
            }
        });
    },
    ingredientsDelete: function (id) { 
        Application.request('ingredients', 'delete', data = {'id': id}).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Изделие успешно удалено!');
                Application.ingredientsGet($('#table-baking-selection').val());
            }
        });
    },


    equipmentGet: function (branch) {
        Application.request('equipment', 'get', data = {'branch': branch}).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                const $tbody = $('tbody');
                $tbody.delay(500).fadeIn().empty();

                response.data.forEach((element) => {
                    const $row = $('<tr>').attr('data-equipment', JSON.stringify(element));

                    const addCell = (text) => {
                        const $cell = $('<td>').text(text);
                        $row.append($cell);
                    };

                    const addHeadingCell = (text) => {
                        const $cell = $('<td>').html('<h6>' + text + '</h6>');
                        $row.append($cell);
                    };

                    addCell('#' + element['id']);
                    addHeadingCell(element['name']);
                    addHeadingCell(Application.g_EquipmentTimeouts[element['sanitizing_interval']]);
                    addHeadingCell(element['last_sanitizing_date']);

                    const $manageButton = $('<a class="btn btn-fw white manageButton">').text('Управление');
                    const $manageCell = $('<td>').append($manageButton);
                    $row.append($manageCell);

                    $tbody.append($row).hide().delay(500).fadeIn();
                });
            }
        });
    },
    equipmentAdd: function () {
        Application.request('equipment', 'add', data = Application.prepareDataForRequest($('#add-modal-form'))).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-add").modal("hide");
                showSuccess('Оборудование успешно добавлен!');
                Application.equipmentGet($('#table-branches-selection').val());
            }
        });
    },
    equipmentEdit: function (id) {
        let data = Application.prepareDataForRequest($('#edit-modal-form'));
        data['id'] = id;
        Application.request('equipment', 'edit', data = data).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Изделие успешно изменено!');
                Application.equipmentGet($('#table-branches-selection').val());
            }
        });
    },
    equipmentDelete: function (id) { 
        Application.request('equipment', 'delete', data = {'id': id}).success((response) => {
            if (response.error) {
                showError(response.message);
            } else {
                $("#modal-edit").modal("hide");
                showSuccess('Изделие успешно удалено!');
                Application.equipmentGet($('#table-branches-selection').val());
            }
        });
    },
}