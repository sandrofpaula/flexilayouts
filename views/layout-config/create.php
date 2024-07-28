<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Layout';
$this->params['breadcrumbs'][] = ['label' => 'Layout Configuration', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="layout-config-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['readonly' => true, 'value' => count($model->getLayouts()) + 1]) ?>
    <?= $form->field($model, 'arquivo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
