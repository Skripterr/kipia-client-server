<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Core\Utils\Headers;

class ErrorController extends Controller
{

    public $headers;

    public $httpResponseCodes = [
        400 => array('400 Bad Request', 'Запрос не может быть обработан из-за синтаксической ошибки.'),
        403 => array('403 Forbidden', 'Сервер отказывается выполнить ваш запрос.'),
        404 => array('404 Not Found', 'Запрошенная страница не найдена.'),
        405 => array('405 Method Not Allowed', 'Метод, указанный в запросе, не разрешен для данного ресурса.'),
        408 => array('408 Request Timeout', 'Ваш браузер не отправил информацию на сервер в течение отведенного времени.'),
        500 => array('500 Internal Server Error', 'Запрос не может быть обработан из-за внутренней ошибки сервера.'),
        502 => array('502 Bad Gateway', 'Сервер получил недопустимый ответ при попытке отправить запрос.'),
        504 => array('504 Gateway Timed Out', 'Сервер не ответил в течение указанного времени ожидания.'),
        510  => array('Ошибка соединения с базой данных', 'Не удалось инициализировать подключение.'),
        511  => array('Ошибка выполнения запроса', 'Сервер не смог обработать ваш запрос.'),
        405  => array('Нарушение безопасности', 'Вернитесь на предыдущую страницу.'),
        1001 => array('Ошибка входа через OAuth', 'Произошла ошибка, попробуйте еще раз.'),
        1002 => array('Техническое обслуживание', 'Сайт недоступен, попробуйте позже.')
    ];

    public function __construct()
    {

        $this->headers = new Headers;
    }

    public function abort(int $httpResponseCode = 404)
    {
        $this->headers->createHeaders($httpResponseCode);

        if (array_key_exists($httpResponseCode, $this->httpResponseCodes)) {
            $title = $this->httpResponseCodes[$httpResponseCode][0];
            $description = $this->httpResponseCodes[$httpResponseCode][1];
        } else {
            $title = $this->httpResponseCodes[404][0];
            $description = $this->httpResponseCodes[404][1];
        }

        if ($httpResponseCode == 510 || $httpResponseCode == 511) {
            die($description);
        }

        parent::__construct();

        $this->view->render(
            'error',
            'template',
            [
                'title' => 'Произошла ошибка...',
                'errorTitle' => $title,
                'errorDescription' => $description,
            ]
        );
        die;
    }
}
