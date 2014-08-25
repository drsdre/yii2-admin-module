<?php

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var asdfstudio\admin\base\Entity $entity
 */

$this->title = $entity->labels[0];
$this->params['breadcrumbs'][] = ['label' => $entity->labels[1], 'url' => ['index', 'entity' => $entity->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'View'), 'url' => ['view', 'entity' => $entity->id, 'id' => $model->primaryKey]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Editing');
?>
<div class="model-update">

    <?= $this->render('_form', [
        'model' => $model,
        'entity' => $entity,
    ]) ?>

</div>
