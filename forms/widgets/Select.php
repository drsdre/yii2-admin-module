<?php

namespace asdfstudio\admin\forms\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use asdfstudio\admin\helpers\AdminHelper;

/**
 * Class Select
 * @package asdfstudio\admin\widgets
 *
 * Renders active select widget with related models
 */
class Select extends InputWidget
{
    /**
     * @var ActiveQuery|array
     */
    public $query;
    /**
     * @var string
     */
    public $labelAttribute;
    /**
     * @var array
     */
    public $items = [];
    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->query instanceof ActiveQuery) {
            if (!$this->labelAttribute) {
                throw new InvalidConfigException('Parameter "labelAttribute" is required');
            }
            $this->items = $this->query->all();
            foreach ($this->items as $i => $model) {
                $this->items[$i] = AdminHelper::resolveAttribute($this->labelAttribute, $model);
            }
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return Html::activeDropDownList($this->model, $this->attribute, $this->items, [
            'class' => 'form-control',
            'multiple' => $this->multiple,
        ]);
    }
}
