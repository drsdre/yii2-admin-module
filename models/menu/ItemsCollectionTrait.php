<?php


namespace asdfstudio\admin\models\menu;


use asdfstudio\admin\AdminItemInterface;
use asdfstudio\admin\models\Item as AdminItem;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

trait ItemsCollectionTrait
{
    /**
     * Items list
     * @var Item[]
     */
    public $items = [];

    /**
     * @param string|AdminItemInterface Item label or [[AdminItemInterface]] object
     * @param [mixed] Url configuation
     * @param [bool|callable] Acive status
     * @param [string] Id of item
     * @return integer Item index
     */
    public function addItem()
    {
        $arguments = func_get_args();
        if (sizeof($arguments) === 1) {
            return $this->addObjectItem($arguments[0]);
        } else {
            $label = ArrayHelper::getValue($arguments, 0, null);
            $url = ArrayHelper::getValue($arguments, 1, null);
            $active = ArrayHelper::getValue($arguments, 2, false);
            $id = ArrayHelper::getValue($arguments, 3, null);
            return $this->addStringItem($label, $url, $active, $id);
        }
    }

    /**
     * @param string $label
     * @param mixed $url
     * @param bool|callable $active
     * @param string $id
     * @return integer
     * @throws \yii\base\InvalidConfigException
     */
    protected function addStringItem($label, $url = null, $active = false, $id = null)
    {
        $item = new Item([
            'label' => $label,
            'url' => $url,
            'active' => $active,
            'id' => $id,
        ]);
        if ($item->validate()) {
            $this->items[] = $item;
            return sizeof($this->items) - 1;
        } else {
            throw new InvalidConfigException('Invalid menu item configuration');
        }
    }

    /**
     * @param string $item
     * @return integer
     */
    protected function addObjectItem($item)
    {
        $label = call_user_func([$item, 'adminLabels']);
        $label = is_array($label) ? $label[0] : $label;
        return $this->addStringItem($label, [
            'manage/index',
            'item' => call_user_func([$item, 'adminSlug']),
        ], false, call_user_func([$item, 'adminSlug']));
    }
}
