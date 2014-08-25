<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use asdfstudio\admin\base\components\AdminFormatter;

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var asdfstudio\admin\base\Entity $entity
 */

$this->title = $entity->labels[0];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['manage/index', 'item' => $entity->id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'View');
?>
<div class="model-view">
    <p>
        <?= Html::a(Yii::t('admin', 'Edit'), ['update', 'entity' => $entity->id, 'id' => $model->primaryKey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('admin', 'Delete'), ['delete', 'entity' => $entity->id, 'id' => $model->primaryKey], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('admin', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <?=DetailView::widget([
            'model' => $model,
            'formatter' => [
                'class' => AdminFormatter::className(),
            ],
            'attributes' => $entity->attributes
        ])?>
    </div>

</div>
