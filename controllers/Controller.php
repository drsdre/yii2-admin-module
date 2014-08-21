<?php


namespace asdfstudio\admin\controllers;


use asdfstudio\admin\models\Item;
use asdfstudio\admin\Module;
use yii\db\ActiveRecord;
use yii\web\Controller as WebController;

/**
 * Class Controller
 * @package asdfstudio\admin\controllers
 * @property Module $module
 */
abstract class Controller extends WebController
{
    public $layout = 'main';

    /**
     * Load registered item
     * @param string $item Item id
     * @return Item
     */
    public function getItem($item)
    {
        if (isset($this->module->items[$item])) {
            return $this->module->items[$item];
        }
        return null;
    }

    /**
     * Load model
     * @param string|Item $item
     * @param string|integer $id
     * @return ActiveRecord mixed
     */
    public function loadModel($item, $id)
    {
        /* @var Item|string $item */
        $item = $this->module->items[(is_string($item) ? $item : $item->id)];

        return call_user_func([$item->class, 'findOne'], $id);
    }
}
