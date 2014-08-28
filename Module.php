<?php


namespace asdfstudio\admin;

use asdfstudio\admin\models\menu\Menu;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Inflector;


class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'asdfstudio\admin\controllers';
    /**
     * URL prefix
     * @var string
     */
    public $urlPrefix = '/admin';
    /**
     * Registered models
     * @var array
     */
    public $entities = [];
    /**
     * Contains Class => Id for fast search
     * @var array
     */
    public $entitiesClasses = [];

    /**
     * Top menu navigation
     * Example configuration
     *
     * ```php
     *  [
     *      [
     *          'label' => 'First item',
     *          'url' => ['index', 'param' => 'value']
     *      ],
     *      [
     *          'label' => 'Dropdown item',
     *          'items' => [
     *              ['label' => 'First child', 'url' => ['first']],
     *              ['label' => 'Second child', 'url' => ['second']],
     *          ]
     *      ]
     *  ]
     * @var Menu
     */
    public $menu;
    /**
     * Sidebar menu navigation
     * Example configuration
     *
     * ```php
     *  [
     *      [
     *          'label' => 'First item',
     *          'url' => ['index', 'param' => 'value']
     *      ],
     *      [
     *          'label' => 'Dropdown item',
     *          'items' => [
     *              ['label' => 'First child', 'url' => ['first']],
     *              ['label' => 'Second child', 'url' => ['second']],
     *          ]
     *      ]
     *  ]
     * ```
     *
     * @var Menu
     */
    public $sidebar;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setViewPath(dirname(__FILE__) . '/views');

        $this->menu = new Menu();
        $this->sidebar = new Menu();
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerRoutes([
            $this->urlPrefix . ''                                             => 'admin/admin/index',
            $this->urlPrefix . '/manage/<entity:[\w\d-_]+>'                   => 'admin/manage/index',
            $this->urlPrefix . '/manage/<entity:[\w\d-_]+>/create'            => 'admin/manage/create',
            $this->urlPrefix . '/manage/<entity:[\w\d-_]+>/<id:[\d]+>'        => 'admin/manage/view',
            $this->urlPrefix . '/manage/<entity:[\w\d-_]+>/<id:[\d]+>/update' => 'admin/manage/update',
            $this->urlPrefix . '/manage/<entity:[\w\d-_]+>/<id:[\d]+>/delete' => 'admin/manage/delete',
        ]);

        $this->registerTranslations();
    }

    /**
     * Register admin module routes
     */
    public function registerRoutes($rules)
    {
        Yii::$app->getUrlManager()->addRules($rules);
    }

    /**
     * Register model in admin. See [[DetailView]] for configuration syntax.
     * @param string $className
     * @param bool $forceRegister
     * @throws \yii\base\InvalidConfigException
     */
    public function registerEntity($className, $forceRegister = false)
    {
        $id = call_user_func([$className, 'slug']);

        if (isset($this->entities[$id]) && !$forceRegister) {
            throw new InvalidConfigException(sprintf('Item with id "%s" already registered', $id));
        }

        $labels = call_user_func([$className, 'labels']);
        $attributes = call_user_func([$className, 'attributes']);
        $attributes =  static::normalizeAttributes($attributes, $className);
        $this->entities[$id] = new $className([
            'id' => $id,
            'modelClass' => $className,
            'labels' => $labels,
            'attributes' => $attributes,
        ]);
        $this->entitiesClasses[$className] = $id;
    }

    /**
     * Register translations
     */
    protected function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['admin'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@vendor/asdf-studio/yii2-admin-module/messages',
        ];
    }

    /**
     * Normalizes the attribute specifications.
     * @throws InvalidConfigException
     */
    public static function normalizeAttributes($attributes, $class = null)
    {
        $model = null;
        if ($class) {
            $modelClass = call_user_func([$class, 'model']);
            $model = new $modelClass;
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
