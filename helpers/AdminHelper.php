<?php


namespace asdfstudio\admin\helpers;


use Yii;
use asdfstudio\admin\Module;
use asdfstudio\admin\base\Entity;

class AdminHelper
{
    /**
     * @param string $entity Entity class name or Id
     * @return Entity|null
     */
    public static function getEntity($entity)
    {
        /* @var Module $module */
        $module = Yii::$app->controller->module;

        if (isset($module->entities[$entity])) {
            return $module->entities[$entity];
        } elseif (isset($module->entitiesClasses[$entity])) {
            return static::getEntity($module->entitiesClasses[$entity]);
        }

        return null;
    }
}
