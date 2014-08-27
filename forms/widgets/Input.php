<?php

namespace asdfstudio\admin\forms\widgets;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class Select
 * @package asdfstudio\admin\widgets
 *
 * Renders active input widget
 */
class Input extends InputWidget
{
    /**
     * HTML input type
     * @var string
     */
    public $type = 'text';

    /**
     * @inheritdoc
     */
    public function run()
    {
        return Html::activeInput($this->type, $this->model, $this->attribute, [
            'class' => 'form-control',
        ]);
    }
}
