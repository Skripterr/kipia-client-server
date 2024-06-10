<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Application\Core\Utils\Request;
use Application\Core\Utils\InputFilter;
use Application\Models\BakingModel;

class bakingApiController extends ApiController
{
    public $baking;

    public function __construct()
    {
        parent::__construct();

        if (!Request::current()->isPOST()) {
            (new ErrorApiController)->abort(405);
        }

        self::validateJWT();
        $this->baking = new BakingModel;
    }

    public function add()
    {
        self::protectedMethod(4);
        $name = InputFilter::filterString(Request::current()->post()->value('name'));
        $weight = InputFilter::filterString(Request::current()->post()->value('weight'));
        $pricing = InputFilter::filterString(Request::current()->post()->value('pricing'));

        if (!InputFilter::checkNotEmpty($name) || !InputFilter::checkNotEmpty($weight) || !InputFilter::checkNotEmpty($pricing)) {
            (new ErrorApiController)->abort(1005);
        }

        if ($this->baking->addBaking($name, $weight, $pricing, $this->user->branch) == 0) {
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

        if (!$this->baking->deleteBaking(['id' => $id])) {
            (new ErrorApiController)->abort(3007);
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
        self::protectedMethod(4);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));
        $name = InputFilter::filterString(Request::current()->post()->value('name'));
        $weight = InputFilter::filterString(Request::current()->post()->value('weight'));
        $pricing = InputFilter::filterString(Request::current()->post()->value('pricing'));

        if (!InputFilter::checkNotEmpty($id) || !InputFilter::checkNotEmpty($name) || !InputFilter::checkNotEmpty($weight) || !InputFilter::checkNotEmpty($pricing)) {
            (new ErrorApiController)->abort(1005);
        }

        if ($this->baking->updateBaking(['name' => $name, 'weight' => $weight, 'pricing' => $pricing], ['id' => $id]) == 0) {
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

    public function get()
    {
        self::protectedMethod(2);
        $record = $this->baking->selectBaking(['branch_id' => $this->user->branch]);

        if (!$record) {
            (new ErrorApiController)->abort(3007);
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
