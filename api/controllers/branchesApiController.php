<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Application\Core\Utils\Request;
use Application\Core\Utils\InputFilter;
use Application\Models\BranchesModel;

class branchesApiController extends ApiController
{
    public $branches;

    public function __construct()
    {
        parent::__construct();

        if (!Request::current()->isPOST()) {
            (new ErrorApiController)->abort(405);
        }

        self::validateJWT();
        $this->branches = new BranchesModel;
    }

    public function add()
    {
        self::protectedMethod(6);
        $address = InputFilter::filterString(Request::current()->post()->value('address'));
        $status = InputFilter::filterString(Request::current()->post()->value('status'));

        if (!InputFilter::checkNotEmpty($address) || !InputFilter::checkNotEmpty($status)) {
            (new ErrorApiController)->abort(1005);
        }

        if ($this->branches->addBranch($address, $status) == 0) {
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
        self::protectedMethod(6);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));

        if (!InputFilter::checkNotEmpty($id)) {
            (new ErrorApiController)->abort(1005);
        }

        if (!$this->branches->deleteBranch(['id' => $id])) {
            (new ErrorApiController)->abort(2007);
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
        self::protectedMethod(6);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));
        $address = InputFilter::filterString(Request::current()->post()->value('address'));
        $status = InputFilter::filterString(Request::current()->post()->value('status'));

        if (!InputFilter::checkNotEmpty($id) || !InputFilter::checkNotEmpty($address) || !InputFilter::checkNotEmpty($status)) {
            (new ErrorApiController)->abort(1005);
        }

        if ($this->branches->updateBranch(['address' => $address, 'status' => $status], ['id' => $id]) == 0) {
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
        self::protectedMethod(5);
        $record = $this->branches->selectBranches([]);

        if (!$record) {
            (new ErrorApiController)->abort(2007);
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
