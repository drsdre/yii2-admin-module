<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use asdfstudio\admin\components\AdminFormatter;

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord */
/* @var $item \asdfstudio\admin\models\Item */

$this->title = $item->label;
$this->params['breadcrumbs'][] = ['label' => $item->label, 'url' => ['manage/index', 'item' => $item->id]];
$this->params['breadcrumbs'][] = 'Просмотр';
?>
<div class="model-view">
    <p>
        <?= Html::a('Update', ['update', 'item' => $item->id, 'id' => $model->primaryKey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'item' => $item->id, 'id' => $model->primaryKey], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
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
