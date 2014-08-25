<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var asdfstudio\admin\base\models\Item $item
 */

$this->title = $item->label;
$this->params['breadcrumbs'][] = ['label' => $item->label, 'url' => ['index', 'item' => $item->id]];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр', 'url' => ['view', 'item' => $item->id, 'id' => $model->primaryKey]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Deleting');
?>
<div class="model-delete">

    <div class="driver-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php echo Html::submitButton(Yii::t('admin', 'Delete'), [
            'class' => 'btn btn-danger'
        ])?>
        <?php echo Html::a('Cancel', [
            'view',
            'item' => $item->id,
            'id' => $model->primaryKey,
        ], [
            'class' => 'btn btn-primary'
        ])?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
