<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Configuração de Layout';
?>
<style>
    .btn-selected {
        background-color: #4CAF50; /* Cor verde personalizada */
        color: white;
    }

    .row-selected {
        background-color: #ecfd51; /* Cor de fundo personalizada para a linha */
        font-weight: bold; /* Fonte em negrito */
    }
</style>

<div class="layout-config-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a('Criar Novo Layout', ['create'], ['class' => 'btn btn-success']) ?></p>

    <p><strong>Legenda:</strong> O layout selecionado está destacado em verde.</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function($layoutModel, $key, $index, $grid) use ($model) {
            return $layoutModel['id'] == $model->selected ? ['class' => 'row-selected'] : [];
        },
        'columns' => [
            'id',
            'arquivo',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{select} {update} {delete}',
                'buttons' => [
                    'select' => function($url, $layoutModel, $key) use ($model) {
                        $isSelected = $layoutModel['id'] == $model->selected;
                        $icon = $isSelected ? 'fas fa-check' : 'fas fa-hand-pointer';
                        $class = $isSelected ? 'btn btn-selected' : 'btn btn-warning';
                        return Html::a("<i class='{$icon}'></i>", ['select', 'id' => $layoutModel['id']], ['class' => $class, 'title' => $isSelected ? 'Selecionado' : 'Selecionar']);
                    },
                    'update' => function($url, $layoutModel, $key) {
                        return Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $layoutModel['id']], ['class' => 'btn btn-primary', 'title' => 'Atualizar']);
                    },
                    'delete' => function($url, $layoutModel, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $layoutModel['id']], [
                            'class' => 'btn btn-danger',
                            'title' => 'Deletar',
                            'data' => [
                                'confirm' => 'Você tem certeza de que deseja excluir este item?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
