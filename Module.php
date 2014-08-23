<?php


namespace asdfstudio\admin;

use asdfstudio\admin\models\Item;
use asdfstudio\admin\models\menu\Menu;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use asdfstudio\admin\helpers\AdminHelper;


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
    public $items = [];
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
            $this->urlPrefix . ''                                           => 'admin/admin/index',
            $this->urlPrefix . '/manage/<item:[\w\d-_]+>'                   => 'admin/manage/index',
            $this->urlPrefix . '/manage/<item:[\w\d-_]+>/create'            => 'admin/manage/create',
            $this->urlPrefix . '/manage/<item:[\w\d-_]+>/<id:[\d]+>'        => 'admin/manage/view',
            $this->urlPrefix . '/manage/<item:[\w\d-_]+>/<id:[\d]+>/update' => 'admin/manage/update',
            $this->urlPrefix . '/manage/<item:[\w\d-_]+>/<id:[\d]+>/delete' => 'admin/manage/delete',
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
    public function registerItem($className, $forceRegister = false)
    {
        $id = call_user_func([$className, 'adminSlug']);

        if (isset($this->items[$id]) && !$forceRegister) {
            throw new InvalidConfigException(sprintf('Item with id "%s" already registered', $id));
        }

        $label = call_user_func([$className, 'adminLabels']);
        $label = (is_array($label)) ? $label[0] : $label;
        $attributes = call_user_func([$className, 'adminAttributes']);
        $attributes =  AdminHelper::normalizeAttributes($attributes, $className);
        $this->items[$id] = new Item([
            'id' => $id,
            'class' => $className,
            'label' => $label,
            'adminAttributes' => $attributes,
        ]);
    }

    /**
     * Register translations
     */
    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['admin'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@vendor/asdf-studio/yii2-admin-module/messages',
        ];
    }
}
