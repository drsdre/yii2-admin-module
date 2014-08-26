<?php

use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var yii\widgets\ActiveForm $form
 * @var asdfstudio\admin\base\Entity $entity
 * @var asdfstudio\admin\forms\Form $formClass
 */
$formOptions = $entity::form();
$formClass = $formOptions['class'];
?>

<div class="model-form row">
    <?php echo $formClass::widget(ArrayHelper::merge([
        'model' => $model,
    ], $formOptions))?>
</div>
