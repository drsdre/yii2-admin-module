<?php


namespace asdfstudio\admin\components;


use asdfstudio\admin\Module;
use Yii;
use asdfstudio\admin\models\Item;
use yii\base\Formatter;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class AdminFormatter extends Formatter
{
    /** Items cache */
    private $_items = [];

    /**
     * Format $value as one of list values
     *
     * ```php
     * [
     *     ...
     *     'format' => ['list', ['value1' => 'This is Value 1', 'value2' => 'This is Value 2']],
     *     ...
     * ]
     * ```
     *
     * @param $value
     * @param array $list
     * @return string
     */
    public function asList($value, $list = [])
    {
        if (is_array($list) && empty($list)) {
            return $this->nullDisplay;
        }
        if (isset($list[$value])) {
            return $list[$value];
        } else {
            return $this->nullDisplay;
        }
    }

    /**
     * Format $value as [[ActiveRecord]] model.
     *
     * ```php
     * [
     *     ...
     *     'format' => ['model', ['labelAttribute' => 'username']],
     *     ...
     * ]
     * ```
     *
     * @param ActiveRecord|ActiveRecord[] $value
     * @param array $options 'labelAttribute' is a related model's attribute to be shown as field value
     * @return string
     */
    public function asModel($value, $options = ['labelAttribute' => 'id'])
    {
        if (is_array($value)) {
            $values = [];
            foreach ($value as $i => $v) {
                $values[$i] = $this->asModel($v, $options);
            }
            return implode(', ', $values);
        }
        $label = $value->getAttribute($options['labelAttribute']);
        $item = $this->getItem($value->className());
        if ($item !== null) {
            return Html::a($label, ['view', 'item' => $item->id, 'id' => $value->primaryKey]);
        }
        return $label;
    }

    /**
     * @param string $class Class name
     * @return Item|null
     */
    protected function getItem($class)
    {
        if (isset($this->_items[$class])) {
            return $this->_items[$class];
        }

        /* @var Module $module */
        $module = Yii::$app->controller->module;

        /* @var Item $item */
        foreach ($module->items as $item) {
            if ($item->class == $class) {
                $this->_items[$class] = $item;
                return $item;
            }
        }
        return null;
    }
}
