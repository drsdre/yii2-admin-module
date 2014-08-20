<?php

namespace asdfstudio\admin\widgets;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use Yii;

/**
 * Class Select
 * @package asdfstudio\admin\widgets
 */
class ActiveSelect extends InputWidget
{
    /**
     * Related models search query
     * @var ActiveQuery
     */
    public $query = null;
    /**
     *
     */
    public $labelAttribute;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->query === null) {
            if (method_exists($this->model, 'get' . $this->attribute)) {
                $this->query = call_user_func([$this->model, 'get' . $this->attribute]);
            } else {
                throw new InvalidConfigException(sprintf(
                    'Getter for "%s" attribute not found in class %s',
                    $this->attribute, $this->model->className()
                ));
            }
        } elseif (!($this->query instanceof ActiveQuery)) {
            throw new InvalidConfigException('Parameter "query" must be an instance of ActiveQuery class');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        list($foreignKey, $currentKey) = $this->getFieldsNames();
        $models = $this->loadModels($foreignKey);
        $selectedModels = $this->loadSelectedModels($foreignKey);

        $result = '';
        $result .= Html::beginTag('select', [
            'name' => $this->model->formName() . '[' . $this->attribute . ']' . ($this->query->multiple ? '[]' : ''),
            'multiple' => $this->query->multiple,
            'class' => 'form-control',
        ]);

        foreach ($models as $index => $model) {
            $label = $this->labelAttribute ? $model->getAttribute($this->labelAttribute) : $model->primaryKey;
            $result .= $this->renderItem([$index, $label], isset($selectedModels[$index]));
        }

        $result .= Html::endTag('select');
        return $result;
    }

    /**
     * ```php
     * $item = $this->renderItem([1, 'Some value'], true);
     * // will return <option value="1" selected="seelcted">Some value</option>
     * ```
     *
     * @param array $item [value, label]
     * @param bool $selected Is selected?
     * @param array $options
     * @return string
     */
    public function renderItem($item, $selected = false, $options = [])
    {
        return Html::tag('option', $item[1], ArrayHelper::merge([
            'value' => $item[0],
            'selected' => $selected,
        ], $options));
    }

    /**
     * Returns joined field name of related model and field name of current model
     * @param ActiveQuery|null define specified query
     * @return array [$foreignKey, $currentKey]
     */
    public function getFieldsNames($query = null)
    {
        if ($query === null) {
            $query = $this->query;
        }
        $foreignKeys = array_keys($query->link);
        $foreignKey = $foreignKeys[0];
        if (!$query->multiple) {
            $currentKey = $query->link[$foreignKey];
        } elseif ($query->multiple && !$query->via) {
            $currentKey = $query->link[$foreignKey];
        } else {
            $keys = $this->getFieldsNames($query->via);
            $currentKey = $keys[1];
        }

        return [$foreignKey, $currentKey];
    }

    /**
     * Load all related models
     * @param string|null $index Index field
     * @return ActiveRecord[]
     */
    public function loadModels($index = null)
    {
        /* @var ActiveQuery $query */
        $query = call_user_func([$this->query->modelClass, 'find']);
        if ($index) {
            $query = $query->indexBy($index);
        }

        return $query->all();
    }

    /**
     * Load all selected models
     * @param string|null $index Index field
     * @return ActiveRecord[]
     */
    public function loadSelectedModels($index = null)
    {
        $query = $this->query;
        if ($index) {
            $query = $query->indexBy($index);
        }

        return $query->all();
    }
}
