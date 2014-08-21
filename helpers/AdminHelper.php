<?php


namespace asdfstudio\admin\helpers;


use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Inflector;

class AdminHelper
{
    /**
     * Normalizes the attribute specifications.
     * @throws InvalidConfigException
     */
    public static function normalizeAttributes($attributes, $class = null)
    {
        $model = null;
        if ($class) {
            $model = new $class([]);
        }

        $newAttributes = [];
        foreach ($attributes as $i => $attribute) {
            if (is_string($attribute)) {
                if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
                    throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }
                $attribute = [
                    'attribute' => $matches[1],
                    'format' => isset($matches[3]) ? $matches[3] : 'text',
                    'label' => isset($matches[5]) ? $matches[5] : null,
                ];
            }

            if (!is_array($attribute)) {
                throw new InvalidConfigException('The attribute configuration must be an array.');
            }

            if (isset($attribute['visible']) && !$attribute['visible']) {
                continue;
            }

            if (!isset($attribute['format'])) {
                $attribute['format'] = 'text';
            }

            if (!isset($attribute['visible'])) {
                $attribute['visible'] = true;
            }
            if (!isset($attribute['editable'])) {
                $attribute['editable'] = true;
            }

            if (!isset($attribute['format'])) {
                $attribute['format'] = 'text';
            }

            if (isset($attribute['attribute'])) {
                $attributeName = $attribute['attribute'];
                if (!isset($attribute['label'])) {
                    $attribute['label'] = $model instanceof Model ? $model->getAttributeLabel($attributeName) : Inflector::camel2words($attributeName, true);
                }
            } elseif (!isset($attribute['label']) || !isset($attribute['attribute'])) {
                throw new InvalidConfigException('The attribute configuration requires the "attribute" element to determine the value and display label.');
            }
            $newAttributes[$i] = $attribute;
        }
        return $newAttributes;
    }
}
