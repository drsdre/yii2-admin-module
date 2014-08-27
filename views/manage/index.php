<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use asdfstudio\admin\components\AdminFormatter;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $modelsProvider
 * @var \asdfstudio\admin\base\Entity $entity
 */

$this->title = $entity->labels[1];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="form-group">
        <?php echo Html::a(Yii::t('admin', 'Create'), ['create', 'entity' => $entity->id], ['class' => 'btn btn-success'])?>
    </div>
</div>

<div class="row">
    <?php
        $columns = [];
        foreach ($entity->attributes as $attribute) {
            $column = [
                'class' => DataColumn::className(),
                'attribute' => $attribute['attribute'],
                'format' => $attribute['format'],
                'label' => $attribute['label'],
                'visible' => $attribute['visible'],
            ];

            $columns[] = $column;
        }
        $columns[] = [
            'class' => ActionColumn::className(),
            'buttons' => [
                'view' => function($url, $model, $key) use ($entity ) {
                    return Html::a('view', ['manage/view', 'entity' => $entity->id, 'id' => $model->id]);
                },
                'update' => function($url, $model, $key) use ($entity ) {
                    return Html::a('update', ['manage/update', 'entity' => $entity->id, 'id' => $model->id]);
                },
                'delete' => function($url, $model, $key) use ($entity ) {
                    return Html::a('delete', ['manage/delete', 'entity' => $entity->id, 'id' => $model->id]);
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