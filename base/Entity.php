<?php


namespace asdfstudio\admin\base;


use asdfstudio\admin\forms\Form;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use ReflectionClass;

/**
 * Class Entity
 * @package asdfstudio\admin
 */
abstract class Entity extends Component
{
    /**
     * Triggers after new model creation
     */
    const EVENT_CREATE_SUCCESS  = 'entity_create_success';
    const EVENT_CREATE_FAIL     = 'entity_create_fail';
    /**
     * Trigers after model updated
     */
    const EVENT_UPDATE_SUCCESS  = 'entity_update_success';
    const EVENT_UPDATE_FAIL     = 'entity_update_fail';
    /**
     * Triggers after model deleted
     */
    const EVENT_DELETE_SUCCESS  = 'entity_delete_success';
    const EVENT_DELETE_FAIL     = 'entity_delete_fail';

    /**
     * @var string Entity Id
     */
    public $id;
    /**
     * @var string Model's class
     */
    public $modelClass;
    /**
     * @var array Labels
     */
    public $labels;
    /**
     * @var array Attributes
     */
    public $attributes;

    /**
     * List of model's attributes for displaying table and view and edit pages configuration
     *
     * ```php
     *  [ // display attributes. @see [[DetailView]] for configuration syntax
     *      'id',
     *      'username',
     *      'bio:html',
     *      'dob:date',
     *      [ // support related models
     *          'attribute' => 'posts', // getter name, e.g. getPosts()
     *          'format' => ['model', ['labelAttribute' => 'title']], // @see [[AdminFormatter]]
     *          'visible' => true, // visible item in list, view, create and update
     *          'editable' => false, // edit item in update and create
     *      ],
     *  ],
     * ```
     *
     * @return array
     */
    public static function attributes() {
        return [];
    }

    /**
     * Should return an array with single and plural form of model name, e.g.
     *
     * ```php
     *  return ['User', 'Users'];
     * ```
     *
     * @return array
     */
    public static function labels() {
        $class = new ReflectionClass(static::className());
        $class = $class->getShortName();

        return [$class, Inflector::pluralize($class)];
    }

    /**
     * Slug for url, e.g.
     * Slug should match regex: [\w\d-_]+
     *
     * ```php
     *  return 'user'; // url will be /admin/manage/user[<id>[/<action]]
     * ```
     *
     * @return string
     */
    public static function slug() {
        return Inflector::slug(static::model());
    }

    /**
     * Model's class name
     *
     * ```php
     *  return vendorname\blog\Post::className();
     * ```
     *
     * @return string
     * @throws InvalidConfigException
     */
    public static function model()
    {
        throw new InvalidConfigException('Entity must have model name');
    }

    /**
     * Class name of form using for update or create operation
     * Default form class is `asdfstudio\admin\base\Form`
     * For configuration syntax see [[[Form]]
     *
     * ```php
     *  return [
     *      'class' => vendorname\blog\forms\PostForm::className(),
     *      'fields' => [
     *          ...
     *      ]
     *  ];
     * ```
     *
     * @return array
     */
    public function form()
    {
        return [
            'class' => Form::className(),
        ];
    }
}
