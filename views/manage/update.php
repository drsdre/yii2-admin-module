<?php

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var asdfstudio\admin\models\Item $item
 */

$this->title = $item->label;
$this->params['breadcrumbs'][] = ['label' => $item->label, 'url' => ['index', 'item' => $item->id]];
$this->params['breadcrumbs'][] = ['label' => 'Просмотр', 'url' => ['view', 'item' => $item->id, 'id' => $model->primaryKey]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="model-update">

    <?= $this->render('_form', [
        'model' => $model,
        'item' => $item,
    ]) ?>

</div>
