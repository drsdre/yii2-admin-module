<?php


namespace asdfstudio\admin\forms;


use Yii;
use asdfstudio\admin\forms\widgets\Button;
use yii\base\InvalidConfigException;
use yii\bootstrap\ActiveForm;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * Class Form
 * @package asdfstudio\admin\forms
 *
 * Renders form with defined fields and layout.
 *
 * ```php
 * // example: two columns form
 *  echo Form::widget([
 *      'fields' => [ // first column
 *          'wrapper' => '<div class="col-md-8">{items}</div>',
 *          'items' => [
 *              [
 *                  'class' => ActiveField::className(), // field widget name
 *                  'attribute' => 'title', // model's attribute name
 *              ],
 *              [
 *                  'class' => DropdownField::className(), // field widget name
 *                  'attribute' => 'author', // model's attribute name
 *                  'query' => User::find()->indexBy('id'), // fill list of possible autohrs
 *              ],
 *              [
 *                  'class' => HtmlField::className(), // field widget name
 *                  'attribute' => 'content', // model's attribute name
 *              ],
 *          ],
 *      ],
 *      [ // second column
 *          'wrapper' => '<div class="col-md-4">{items}</div>',
 *          'items' => [
 *              [
 *                  'class' => DropdownField::className(), // field widget name
 *                  'attribute' => 'tags', // model's attribute name
 *              ],
 *              [
 *                  'class' => Button::className(), // renders button, instead of input field,
 *                  'label' => 'Publish',
 *                  'action' => function($model) { // can be callable or string
 *                      return $model->publish();
 *                  },
 *              ],
 *          ],
 *      ],
 *  ]);
 *
 * ```
 */
class Form extends ActiveForm
{
    /**
     * Model used in form
     * @var ActiveRecord
     */
    public $model;
    /**
     * Fields list
     * @var array
     */
    public $fields = [];
    /**
     * If true, "Save" button will be rendered at end of form
     * @var bool
     */
    public $renderSaveButton = true;

    public function init()
    {
        parent::init();
        if ($this->renderSaveButton) {
            $this->fields[] = [
                'wrapper' => '<div class="form-group">{items}</div>',
                'items' => [
                    [
                        'class' => Button::className(),
                        'label' => Yii::t('admin', 'Save'),
                        'options' => [
                            'class' => 'btn btn-success',
                        ],
                    ],
                ]
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->renderForm($this->fields) . Html::endForm();
    }

    /**
     * Renders form with fields
     * @param array $fields
     * @return string
     * @throws InvalidConfigException
     */
    public function renderForm($fields)
    {
        if (!is_array($fields)) {
            throw new InvalidConfigException('Parameter "fields" must be an array');
        } elseif (isset($fields['class'])) {
            if (is_a($fields['class'], Button::className(), true)) {
                return Button::widget(ArrayHelper::merge([
                    'tagName' => 'input',
                    'options' => [
                        'name' => Inflector::slug($fields['label']),
                        'type' => 'submit',
                        'value' => $fields['label']
                    ],
                ], $fields));
            } else {
                if (!isset($fields['attribute'])) {
                    throw new InvalidConfigException('Layout\'s field config must have "attribute" property');
                }
                return $this->field($this->model, $fields['attribute'], $fields);
            }
        } elseif (isset($fields['items'])) {
            $items = $this->renderForm($fields['items']);
            if (isset($fields['wrapper'])) {
                $items = strtr($fields['wrapper'], ['{items}' => $items]);
            }
            return $items;
        } else {
            $out = '';
            foreach ($fields as $field) {
                if (is_array($field)) {
                    $out .= $this->renderForm($field);
                }
            }
            return $out;
        }
    }
}
