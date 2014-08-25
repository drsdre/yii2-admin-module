<?php

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var asdfstudio\admin\base\Entity $entity
 */

$this->title = $entity->labels[0];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index', 'item' => $entity->id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Creating');
?>
<div class="model-create">

    <?= $this->render('_form', [
        'model' => $model,
        'entity' => $entity,
    ]) ?>

</div>
