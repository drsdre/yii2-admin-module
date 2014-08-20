<?php


namespace asdfstudio\admin\models;


use yii\base\Model;

/**
 * Class Item
 * @package asdfstudio\admin\models
 */
class Item extends Model
{
    /**
     * Model unique identificator
     * @var string
     */
    public $id;
    /**
     * Model class name
     * @var string
     */
    public $class;
    /**
     * Human readable name
     * @var string
     */
    public $label;
    /**
     * Available model attributes
     * @var array
     */
    public  $modelAttributes = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'class', 'label', 'modelAttributes'], 'safe'],
        ];
    }
}
