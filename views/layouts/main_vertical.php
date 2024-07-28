<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerCssFile('@web/css/menu_vertical.css'); // Adicionando o arquivo CSS externo
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid d-flex align-items-center">
            <div class="d-flex align-items-center">
                <button class="toggle-btn me-2" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                <a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?></a>
            </div>
        </div>
    </nav>
</header>

<aside>
    <ul class="vertical-nav" id="sidebar">
        <li>
            <a href="<?= Url::to(['/site/index']) ?>" class="nav-link">
                <i class="fas fa-home icon"></i>
                <span class="link-text">Home</span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link" onclick="toggleSubmenu(this)">
                <i class="fas fa-plus icon"></i>
                <span class="link-text"> Extra</span>
            </a>
            <ul class="submenu">
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-1-square'></i>Home</a></li>
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-2-square'></i>Home</a></li>
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-3-square'></i>Home</a></li>
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-4-square'></i>Home</a></li>
            </ul>
            
        </li>
        <li>
            <a href="#" class="nav-link" onclick="toggleSubmenu(this)">
                <i class='bi bi-plus-circle-fill'></i>
                <span class="link-text"> Extra</span>
            </a>
            <ul class="submenu">
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-1-square-fill'></i>Home</a></li>
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-2-square-fill'></i>Home</a></li>
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-3-square-fill'></i>Home</a></li>
                <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-4-square-fill'></i>Home</a></li>
                <li>
                    <a href="#" class="nav-link" onclick="toggleSubmenu(this)">
                    <i class='bi bi-shield-fill-plus'></i>
                    <span class="link-text"> Extra</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-1-circle-fill'></i>Home</a></li>
                        <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-2-circle-fill'></i>Home</a></li>
                        <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-3-circle-fill'></i>Home</a></li>
                        <li><a href="<?= Url::to(['/site/index']) ?>" class="nav-link"><i class='bi bi-4-circle-fill'></i>Home</a></li>
                    </ul>
                </li>
            </ul>
            
        </li>
        <li>
            <a href="<?= Url::to(['/site/about']) ?>" class="nav-link">
                <i class="fas fa-info-circle icon"></i>
                <span class="link-text"> Sobre</span>
            </a>
        </li>
        <li>
            <a href="<?= Url::to(['/site/contact']) ?>" class="nav-link">
                <i class="fas fa-envelope icon"></i>
                <span class="link-text"> Contact</span>
            </a>
        </li>
        <li>
            <a href="<?= Url::to(['/layout-config/']) ?>" class="nav-link">
                <i class="bi bi-layout-wtf icon"></i>
                <span class="link-text">Layout</span>
            </a>
        </li>
        <li>
            <?php if (Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to(['/site/login']) ?>" class="nav-link">
                    <i class="fas fa-sign-in-alt icon"></i>
                    <span class="link-text">Login</span>
                </a>
            <?php else: ?>
                <a href="<?= Url::to(['/site/logout']) ?>" class="nav-link" data-method="post">
                    <i class="fas fa-sign-out-alt icon"></i>
                    <span class="link-text">Logout (<?= Yii::$app->user->identity->username ?>)</span>
                </a>
            <?php endif; ?>
        </li>
    </ul>
</aside>

<main id="main" class="flex-shrink-0 content" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<footer id="footer" class="mt-auto py-3 bg-light footer">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">sandrofpaula@gmail.com <?= date('Y') ?> | @sandrofpaula</div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>
</footer>
<?php
$script = <<<JS
$(document).ready(function() {
    window.toggleSubmenu = function(element) {
        $(element).next('.submenu').toggleClass('show');
    };

    $('#sidebarToggle').on('click', function() {
        $('#sidebar').toggleClass('collapsed');
        $('.content').toggleClass('collapsed');
        $('.footer').toggleClass('collapsed');
    });

    $('#sidebar .nav-link').on('click', function() {
        if ($('#sidebar').hasClass('collapsed')) {
            $('#sidebar').removeClass('collapsed');
            $('.content').removeClass('collapsed');
            $('.footer').removeClass('collapsed');
        }
    });
});
JS;
$this->registerJs($script);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
