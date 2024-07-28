<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'FlexiLayouts';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Bem-vindo ao FlexiLayouts!</h1>

        <p class="lead">O sistema FlexiLayouts permite alterar layouts em tempo de execução, proporcionando flexibilidade e personalização para sua aplicação.</p>

        <p><a class="btn btn-lg btn-success" target="_blank" href="https://www.yiiframework.com">Comece a usar o Yii</a></p>
        
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Flexibilidade</h2>

                <p>O FlexiLayouts permite que você altere os layouts da sua aplicação em tempo de execução, oferecendo uma experiência personalizada para os usuários. Com uma interface intuitiva, você pode configurar diferentes layouts para diferentes necessidades.</p>

                <!-- <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/doc/">Documentação do Yii &raquo;</a></p> -->
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Fácil Configuração</h2>

                <p>A configuração do FlexiLayouts é simples e rápida. Você pode definir e selecionar layouts através de uma interface amigável, sem a necessidade de reiniciar o servidor. Isso proporciona agilidade e praticidade na administração do sistema.</p>
                
                <p><a class="btn btn-info" href="<?= Url::to(['/layout-config/']) ?>"><i class="bi bi-layout-wtf icon"></i> Configurar Layout &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Extensibilidade</h2>

                <p>Com FlexiLayouts, você pode facilmente estender as funcionalidades do seu sistema. Através de módulos e extensões, você pode adicionar novas características e melhorar a experiência do usuário, mantendo a flexibilidade e a personalização.</p>

                <!-- <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/extensions/">Extensões do Yii &raquo;</a></p> -->
            </div>
        </div>

    </div>
</div>
