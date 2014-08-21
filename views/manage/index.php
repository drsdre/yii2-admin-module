<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use asdfstudio\admin\components\AdminFormatter;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $modelsProvider
 * @var \asdfstudio\admin\models\Item $item
 */

$this->title = $item->label;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="form-group">
        <?php echo Html::a('Create', ['create', 'item' => $item->id], ['class' => 'btn btn-success'])?>
    </div>
</div>

<div class="row">
    <?php
        $columns = [];
        foreach ($item->modelAttributes as $attribute) {
            $column = [
                'class' => DataColumn::className(),
                'attribute' => $attribute['attribute'],
                'format' => $attribute['format'],
                'label' => $attribute['label'],
            ];

            $columns[] = $column;
        }
        $columns[] = [
            'class' => ActionColumn::className(),
            'buttons' => [
                'view' => function($url, $model, $key) use ($item ) {
                    return Html::a('view', ['manage/view', 'item' => $item->id, 'id' => $model->id]);
                },
                'update' => function($url, $model, $key) use ($item ) {
                    return Html::a('update', ['manage/update', 'item' => $item->id, 'id' => $model->id]);
                },
                'delete' => function($url, $model, $key) use ($item ) {
                    return Html::a('delete', ['manage/delete', 'item' => $item->id, 'id' => $model->id]);
                },
            ],
        ];
    ?>
    <?=GridView::widget([
        'dataProvider' => $modelsProvider,
        'columns' => $columns,
        'formatter' => [
            'class' => AdminFormatter::className(),
        ],
    ])?>
</div>