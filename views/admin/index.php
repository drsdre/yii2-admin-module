<?php

use asdfstudio\block\Block;

/**
 * @var \yii\web\View $this
 */

$this->title = Yii::t('admin', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <?php echo Block::show('admin.main.dashboard')?>
</div>