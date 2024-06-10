<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Application\Core\Utils\Request;
use Application\Core\Utils\InputFilter;
use Application\Models\EquipmentModel;

class equipmentApiController extends ApiController
{
    public $equipment;

    public function __construct()
    {
        parent::__construct();

        if (!Request::current()->isPOST()) {
            (new ErrorApiController)->abort(405);
        }

        self::validateJWT();
        $this->equipment = new EquipmentModel;
    }

    public function add()
    {
        self::protectedMethod(4);
        $name = InputFilter::filterString(Request::current()->post()->value('name'));
        $sanitizingInterval = InputFilter::filterString(Request::current()->post()->value('sanitizing_interval'));
        $lastSanitizingDate = InputFilter::filterString(Request::current()->post()->value('last_sanitizing_date'));

        if (!InputFilter::checkNotEmpty($name) || !InputFilter::checkNotEmpty($sanitizingInterval) || !InputFilter::checkNotEmpty($lastSanitizingDate)) {
            (new ErrorApiController)->abort(1005);
        }

        try 
        {
            $lastSanitizingDate = date("Y-m-d H:i:s", strtotime($lastSanitizingDate));
        } catch (\Exception $e) 
        {
            (new ErrorApiController)->abort(5008);
        }

        if ($this->equipment->addEquipment($name, $this->user->branch, $sanitizingInterval, $lastSanitizingDate) == 0) {
            (new ErrorApiController)->abort(511);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false],
            ]
        );
    }

    public function delete()
    {
        self::protectedMethod(4);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));

        if (!InputFilter::checkNotEmpty($id)) {
            (new ErrorApiController)->abort(1005);
        }

        if (!$this->equipment->deleteEquipment(['id' => $id])) {
            (new ErrorApiController)->abort(5007);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false]
            ]
        );
    }

    public function edit()
    {
        self::protectedMethod(0);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));
        $name = InputFilter::filterString(Request::current()->post()->value('name'));
        $sanitizingInterval = InputFilter::filterString(Request::current()->post()->value('sanitizing_interval'));
        $lastSanitizingDate = InputFilter::filterString(Request::current()->post()->value('last_sanitizing_date'));

        if (!InputFilter::checkNotEmpty($name) || !InputFilter::checkNotEmpty($sanitizingInterval) || !InputFilter::checkNotEmpty($lastSanitizingDate)) {
            (new ErrorApiController)->abort(1005);
        }

        try 
        {
            $lastSanitizingDate = date("Y-m-d H:i:s", strtotime($lastSanitizingDate));
        } catch (\Exception $e) 
        {
            (new ErrorApiController)->abort(5008);
        }

        if ($this->equipment->updateEquipment(['name' => $name, 'sanitizing_interval' => $sanitizingInterval, 'last_sanitizing_date' => $lastSanitizingDate], ['id' => $id]) == 0) {
            (new ErrorApiController)->abort(511);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false]
            ]
        );
    }

    public function get()
    {
        self::protectedMethod(0);
        $type = InputFilter::filterString(Request::current()->post()->value('type'));

        if (!InputFilter::checkNotEmpty($type)) {
            (new ErrorApiController)->abort(1005);
        }

        $typeTimeDelta = $type * 3600;

        //SELECT * FROM equipment WHERE '2024-06-11 00:39:00' > DATE_ADD(last_sanitizing_date, INTERVAL sanitizing_interval * 3600 SECOND);
        // date("Y-m-d H:i:s",

        $record = $this->equipment->selectEquipment(['branch_id' => $this->user->branch]);

        if (!$record) {
            (new ErrorApiController)->abort(5007);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false, 'data' => $record]
            ]
        );
    }
}
