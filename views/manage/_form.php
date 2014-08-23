<?php

use yii\helpers\Html;
use asdfstudio\admin\widgets\ActiveSelect;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget as ImperaviWidget;

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var yii\widgets\ActiveForm $form
 * @var asdfstudio\admin\models\Item $item
 */
?>

<div class="model-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?php foreach($item->adminAttributes as $attribute) {
            $format = (is_array($attribute['format']) ? $attribute['format'][0] : $attribute['format']);

            if ($format == 'model' || $form == 'models') {
                echo $form->field($model, $attribute['attribute'])->widget(ActiveSelect::className(), [
                    'options' => $attribute,
                    'labelAttribute' => (is_array($attribute['format']) ? $attribute['format'][1]['labelAttribute'] : $attribute['format'])
                ]);
            } elseif ($format == 'html') {
                echo $form->field($model, $attribute['attribute'])->widget(ImperaviWidget::className(), [
                    'settings' => [
                        'lang' => Yii::$app->language,
                        'minHeight' => 400,
                        'pastePlainText' => true,
                        'plugins' => [
                            'clips',
                        ]
                    ]
                ]);
            } elseif ($format == 'list') {
                $list = (is_array($attribute['format']) ? $attribute['format'][1] : []);
                echo $form->field($model, $attribute['attribute'])->dropDownList($list);
            } else {
                echo $form->field($model, $attribute['attribute']);
            }
        }?>
    </div>

    <?php echo Html::submitButton(Yii::t('admin', 'Save'), [
        'class' => 'btn btn-success'
    ])?>

    <?php ActiveForm::end(); ?>

</div>
