<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use asdfstudio\admin\components\AdminFormatter;

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord */
/* @var $item \asdfstudio\admin\models\Item */

$this->title = $item->label;
$this->params['breadcrumbs'][] = ['label' => $item->label, 'url' => ['manage/index', 'item' => $item->id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Viewing');
?>
<div class="model-view">
    <p>
        <?= Html::a(Yii::t('admin', 'Edit'), ['update', 'item' => $item->id, 'id' => $model->primaryKey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('admin', 'Delete'), ['delete', 'item' => $item->id, 'id' => $model->primaryKey], [
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
            'attributes' => $item->adminAttributes
        ])?>
    </div>

</div>
