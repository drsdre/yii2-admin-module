<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use asdfstudio\admin\AdminAsset;
use frontend\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
<div id="wrapper">

    <?php
        NavBar::begin([
//            'brandLabel' => 'My Company',
//            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-inverse navbar-fixed-top',
            ],
            'renderInnerContainer' => false,
        ]);

        echo Nav::widget([
            'options' => ['class' => 'top-nav navbar-right nav'],
            'items' => Yii::$app->controller->module->menu->toArray(),
        ]);

    ?>

    <?php /* Yii's Nav widget doesn't works with SB-Admin sidebar CSS */ ?>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <?php foreach(Yii::$app->controller->module->sidebar->toArray() as $i => $menuItem):?>
                <li <?php if(ArrayHelper::getValue($menuItem, 'active/, false')):?>class="active"<?php endif?>>
                <?php if(!isset($menuItem['items'])):?>
                    <a href="<?=Url::to($menuItem['url'])?>"><?=$menuItem['label']?></a>
                <?php else:?>
                    <a href="javascript:;" data-toggle="collapse" data-target="#sidebar-menu<?=$i?>"><?=$menuItem['label']?> <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="sidebar-menu<?=$i?>" class="collapse">
	                    <?php $items = isset($menuItem['items']) ? $menuItem['items'] : []?>
	                    <?php foreach($items as $item):?>
                        <li>
                            <a href="<?=Url::to($item['url'])?>"><?=$item['label']?></a>
                        </li>
		                <?php endforeach?>
                    </ul>
                <?php endif?>
                </li>
            <?php endforeach?>
        </ul>
    </div>
    <!-- /.navbar-collapse -->

    <?php
        NavBar::end();
    ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?=$this->title?> <small><?=(isset($this->params['description']) ? $this->params['description'] : '')?></small>
                    </h1>
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php $this->endBody() ?>
<script>
	$('select').selectpicker();
</script>
</body>
</html>
<?php $this->endPage() ?>
