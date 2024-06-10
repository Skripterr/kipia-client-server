<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Application\Core\Utils\Request;
use Application\Core\Utils\InputFilter;
use Application\Models\IngredientsModel;

class ingredientsApiController extends ApiController
{
    public $ingredients;

    public function __construct()
    {
        parent::__construct();

        if (!Request::current()->isPOST()) {
            (new ErrorApiController)->abort(405);
        }

        self::validateJWT();
        $this->ingredients = new IngredientsModel;
    }

    public function add()
    {
        self::protectedMethod(2);
        $name = InputFilter::filterString(Request::current()->post()->value('name'));
        $weight = InputFilter::filterString(Request::current()->post()->value('weight'));
        $bakingId = InputFilter::filterString(Request::current()->post()->value('baking_id'));

        if (!InputFilter::checkNotEmpty($name) || !InputFilter::checkNotEmpty($weight) || !InputFilter::checkNotEmpty($bakingId)) {
            (new ErrorApiController)->abort(1005);
        }

        if ($this->ingredients->addIngredients($name, $weight, $bakingId) == 0) {
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
        self::protectedMethod(2);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));

        if (!InputFilter::checkNotEmpty($id)) {
            (new ErrorApiController)->abort(1005);
        }

        if (!$this->ingredients->deleteIngredients(['id' => $id])) {
            (new ErrorApiController)->abort(4007);
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
        self::protectedMethod(2);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));
        $name = InputFilter::filterString(Request::current()->post()->value('name'));
        $weight = InputFilter::filterString(Request::current()->post()->value('weight'));
        $bakingId = InputFilter::filterString(Request::current()->post()->value('baking_id'));

        if (!InputFilter::checkNotEmpty($id) || !InputFilter::checkNotEmpty($name) || !InputFilter::checkNotEmpty($weight) || !InputFilter::checkNotEmpty($bakingId)) {
            (new ErrorApiController)->abort(1005);
        }

        if ($this->ingredients->updateIngredients(['name' => $name, 'weight' => $weight, 'baking_id' => $bakingId], ['id' => $id]) == 0) {
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

        $baking_id = InputFilter::filterString(Request::current()->post()->value('baking_id'));

        if (!InputFilter::checkNotEmpty($baking_id)) {
            (new ErrorApiController)->abort(1005);
        }

        $record = $this->ingredients->selectIngredients(['baking_id' => $baking_id]);

        if (!$record) {
            (new ErrorApiController)->abort(4007);
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
