<?php

namespace asdfstudio\admin\models\menu;


use yii\base\Model;

/**
 * Class Category
 * @package asdfstudio\admin\models\menu
 */
class Category extends Model
{
    use ItemsCollectionTrait;

    /**
     * @var Category label
     */
    public $label;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['label', 'string', 'length' => [1, 255]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            foreach ($this->items as $item) {
                if (!$item->validate()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Render category into array
     * @return array
     */
    public function toArray()
    {
        $res = ['label' => $this->label, 'items' => []];

        foreach ($this->items as $item) {
            $res['items'][] = $item->toArray();
        }

        return $res;
    }
}
