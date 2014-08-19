<?php

namespace asdfstudio\admin\models\menu;


use yii\base\Model;

class Menu extends Model
{
    use ItemsCollectionTrait {
        addItem as addItemOriginal;
    }

    /**
     * Categories with items
     * @var Category[]
     */
    public $categories = [];
    /**
     * Render order
     * @var array
     */
    protected $order = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            foreach ($this->categories as $category) {
                if (!$category->validate()) {
                    return false;
                }
            }
            foreach ($this->items as $item) {
                if (!$item->validate()) {
                    return false;
                }
            }
        }
        return true;
    }

    public function addCategory($label)
    {
        $category = new Category([
            'label' => $label,
        ]);
        $this->categories[] = $category;

        $index = sizeof($this->categories) - 1;
        $this->order[] = ['category', $index];

        return $category;
    }

    /**
     * @inheritdoc
     */
    public function addItem()
    {
        $index = call_user_func_array([$this, 'addItemOriginal'], func_get_args());

        $this->order[] = ['item', $index];
        return $index;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $res = [];

        foreach ($this->order as $order) {
            if ($order[0] == 'category') {
                $res[] = $this->categories[$order[1]]->toArray();
            } elseif ($order[0] == 'item') {
                $res[] = $this->items[$order[1]]->toArray();
            }
        }

        return $res;
    }
}
