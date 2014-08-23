<?php

use asdfstudio\block\Block;

/**
 * @var \yii\web\View $this
 */

$this->title = Yii::t('admin', 'Admin dashboard');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <?php echo Block::show('admin.main.dashboard')?>
</div>