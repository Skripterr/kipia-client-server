<?php

namespace Api\Controllers;

use Application\Core\Controller;
use Application\Core\Utils\Headers;
use Application\Core\Utils\Request;

class ErrorApiController extends Controller
{
    public $headers;

    public $httpResponseCodes = [
        1 => ['Site on maintenance, try later.', 'Сайт на техническом обслуживании, попробуйте позже.'],
        400 => ['Incorrect api request.', 'Неверный запрос к API.'],
        403 => ['Forbidden api request.', 'Запрос к API запрещен.'],
        404 => ['Api endpoint not found.', 'Этот API метод не найден.'],
        405 => ['Incorrect http method.', 'Этот HTTP метод запрещен.'],
        408 => ['Request timed out.', 'Время ожидения истекло.'],
        500 => ['Internal server error.', 'Внутренняя ошибка сервера.'],
        502 => ['Internal server error', 'Внутренняя ошибка шлюза сервера.'],
        504 => ['Endpoint is busy, try later.', 'Этот метод API перегружен, попробуйте позже.'],
        510 => ['Can\'t connect to database.', 'Невозможно соединиться с базой данных.'],
        511 => ['Can\'t process request to database.', 'Невозможно выполнить запрос к базе данных.'],
        999 => ['Value didn\'t changed.', 'Значение не изменилось.'],
        1000 => ['Can\'t process request to database.', 'Невозможно выполнить запрос к базе данных.'],
        1001 => ['JSON Web Token is incorrect.', 'Неверный веб-токен JSON.'],
        1002 => ['This api method is disabled.', 'Этот метод API отключен.'],
        1003 => ['JSON Web Token is expired.', 'Срок действия веб-токена JSON истёк.'],
        1004 => ['Incorrect username or password.', 'Неверный логин или пароль.'],
        1005 => ['Fill in all the fields.', 'Заполните все поля.'],
        1006 => ['Username already exists in database.', 'Логин уже существует в базе данных.'],
        1007 => ['Users with this data not found.', 'Пользователи с указанными данными не найдены.'],
        2007 => ['Branches with this data not found.', 'Филиалы с указанными данными не найдены.'],
        3007 => ['Products with this data not found.', 'Изделия с указанными данными не найдены.'],
        4007 => ['Ingredients with this data not found.', 'Ингредиенты с указанными данными не найдены.'],
        5007 => ['Equipment with this data not found.', 'Оборудование с указанными данными не найдены.'],
        5008 => ['Incorrect datetime format.', 'Неверный формат метки времени.'],
    ];

    public function __construct()
    {
        $this->headers = new Headers;
    }

    public function abort(int $httpResponseCode = 200, string $errorCodeMessage = '')
    {
        if (array_key_exists($httpResponseCode, $this->httpResponseCodes)) {
            $description = $this->httpResponseCodes[$httpResponseCode][((mb_strtolower(Request::Current()->request()->value('locale')) == 'ru') ? 1 : 0)];
        } else {
            $description = $this->httpResponseCodes[404][((mb_strtolower(Request::Current()->request()->value('locale')) == 'ru') ? 1 : 0)];
        }

        parent::__construct();

        if ($httpResponseCode < 1000) {
            $httpResponseCode = 200;
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => $httpResponseCode,
                'response' => ['error' => true, 'message' => $description . $errorCodeMessage],
            ]
        );
    }
}
