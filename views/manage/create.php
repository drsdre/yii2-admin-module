<?php

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var asdfstudio\admin\models\Item $item
 */

$this->title = $item->label;
$this->params['breadcrumbs'][] = ['label' => $item->label, 'url' => ['index', 'item' => $item->id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Creating');
?>
<div class="model-create">

    <?= $this->render('_form', [
        'model' => $model,
        'item' => $item,
    ]) ?>

</div>
