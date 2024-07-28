<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

| Passo | Descrição em Português - BR | Descrição em Inglês |
|-------|-----------------------------|---------------------|
| 1     | Criar pasta `data` na pasta `web`. | Create a `data` folder inside the `web` folder. |
| 2     | Criar o arquivo `layout_config.json` dentro da pasta `data`. | Create the `layout_config.json` file inside the `data` folder. |
| 3     | Criar o arquivo `LayoutConfigController.php` na pasta `controllers` com o código. | Create the `LayoutConfigController.php` file in the `controllers` folder with the code. |
| 4     | Criar o arquivo `LayoutConfig.php` na pasta `models` com o código. | Create the `LayoutConfig.php` file in the `models` folder with the code. |
| 5     | Criar as views `index.php`, `create.php`, `update.php` na pasta `layout-config`. | Create the `index.php`, `create.php`, `update.php` views in the `layout-config` folder. |
| 6     | Ajustar o arquivo de configuração `config/web.php`. | Adjust the `config/web.php` configuration file. |
| 7     | Criar os arquivos de layout `main_horizontal.php` e `main_vertical.php` na pasta `views/layouts`. | Create the `main_horizontal.php` and `main_vertical.php` layout files in the `views/layouts` folder. |
| 8     | Criar o arquivo `menu_vertical.css` na pasta `web/css`. | Create the `menu_vertical.css` file in the `web/css` folder. |

### Código para o Passo 3: LayoutConfigController.php

```php
<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LayoutConfig;

class LayoutConfigController extends Controller
{
    public function actionIndex()
    {
        $model = new LayoutConfig();
        $dataProvider = $model->getDataProvider();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->saveData();
            Yii::$app->session->setFlash('success', 'Configuração de layout atualizada com sucesso.');
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new LayoutConfig();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $layouts = $model->getLayouts();
            $newId = count($layouts) + 1;
            $model->addLayout($newId, $model->arquivo);
            Yii::$app->session->setFlash('success', 'Layout criado com sucesso.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = new LayoutConfig();
        $layouts = $model->getLayouts();
        $layout = array_filter($layouts, function($layout) use ($id) {
            return $layout['id'] == $id;
        });
        $layout = reset($layout);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->updateLayout($id, $model->arquivo);
            Yii::$app->session->setFlash('success', 'Layout atualizado com sucesso.');
            return $this->redirect(['index']);
        }

        $model->id = $layout['id'];
        $model->arquivo = $layout['arquivo'];

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = new LayoutConfig();
        $model->deleteLayout($id);
        Yii::$app->session->setFlash('success', 'Layout excluído com sucesso.');
        return $this->redirect(['index']);
    }

    public function actionSelect($id)
    {
        $model = new LayoutConfig();
        $model->selected = $id;
        $model->saveData();
        Yii::$app->session->setFlash('success', 'Layout selecionado com sucesso.');
        return $this->redirect(['index']);
    }
}
?>
```

### Código para o Passo 4: LayoutConfig.php

```php
<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class LayoutConfig extends Model
{
    public $id;
    public $arquivo;
    public $selected;

    private $filePath;
    private $layouts;

    public function __construct($config = [])
    {
        $this->filePath = Yii::getAlias('@webroot/data/layout_config.json');
        $this->loadData();
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'arquivo'], 'required'],
            [['id', 'selected'], 'integer'],
            [['arquivo'], 'string', 'max' => 255],
        ];
    }

    public function loadData()
    {
        if (file_exists($this->filePath)) {
            $data = Json::decode(file_get_contents($this->filePath), true);
            $this->layouts = $data['layouts'] ?? [];
            $this->selected = $data['selected'] ?? 1;
        }
    }

    public function saveData()
    {
        $data = [
            'layouts' => $this->layouts,
            'selected' => $this->selected,
        ];
        file_put_contents($this->filePath, Json::encode($data));
    }

    public function getLayouts()
    {
        return $this->layouts;
    }

    public function getLayoutOptions()
    {
        $options = [];
        foreach ($this->layouts as $layout) {
            $options[$layout['id']] = $layout['arquivo'];
        }
        return $options;
    }

    public function addLayout($id, $arquivo)
    {
        $this->layouts[] = ['id' => $id, 'arquivo' => $arquivo];
        $this->saveData();
    }

    public function updateLayout($id, $arquivo)
    {
        foreach ($this->layouts as &$layout) {
            if ($layout['id'] == $id) {
                $layout['arquivo'] = $arquivo;
            }
        }
        $this->saveData();
    }

    public function deleteLayout($id)
    {
        $this->layouts = array_filter($this->layouts, function($layout) use ($id) {
            return $layout['id'] != $id;
        });
        $this->saveData();
    }

    public function getDataProvider()
    {
        return new ArrayDataProvider([
            'allModels' => $this->layouts,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'arquivo'],
            ],
        ]);
    }
}
?>
```

### Código para o Passo 5: Views

#### `index.php`

```php
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
                    'delete

' => function($url, $layoutModel, $key) {
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
```

#### `create.php`

```php
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
```

#### `update.php`

```php
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Layout: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Layout Configuration', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="layout-config-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'arquivo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
```

### Código para o Passo 6: Ajustar `config/web.php`

```php
// Leitura do Arquivo JSON para Seleção de Layout
$layoutConfigPath = __DIR__ . '/../web/data/layout_config.json';
$selectedLayout = 'main'; // Default layout

if (file_exists($layoutConfigPath)) {
    $layoutConfig = json_decode(file_get_contents($layoutConfigPath), true);
    $selectedLayoutId = $layoutConfig['selected'] ?? 1;
    $selectedLayout = array_filter($layoutConfig['layouts'], function($layout) use ($selectedLayoutId) {
        return $layout['id'] == $selectedLayoutId;
    });
    $selectedLayout = reset($selectedLayout);
    $selectedLayout = $selectedLayout['arquivo'] ?? 'main';
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout' => $selectedLayout, // Define o layout selecionado
    // outras configurações
];
```

### Código para o Passo 8: Arquivo `menu_vertical.css`

```css
/* \giiwizard\web\css\menu_vertical.css */

.container, .container-lg, .container-md, .container-sm, .container-xl {
    max-width: 1500px;
}
.vertical-nav {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 250px;
    position: fixed;
    top: 56px; /* Ajuste conforme a altura do seu navbar */
    left: 0;
    height: 100%;
    overflow-y: auto;
    background-color: #343a40;
    transition: width 0.3s;
}
.vertical-nav.collapsed {
    width: 70px;
}
.vertical-nav > li {
    margin-bottom: 10px;
}
.vertical-nav .nav-link {
    display: flex;
    align-items: center;
    padding: 10px;
    color: #fff;
    text-decoration: none;
}
.vertical-nav .nav-link .icon {
    margin-right: 10px;
}
.vertical-nav.collapsed .nav-link .link-text {
    display: none;
}
.vertical-nav .nav-link:hover {
    background-color: #495057;
}
.vertical-nav .submenu {
    list-style: none;
    padding-left: 20px;
    display: none;
}
.vertical-nav .submenu.show {
    display: block;
}
.vertical-nav.collapsed .submenu {
    display: none;
}
.content {
    margin-left: 250px; /* Adjust based on the width of the sidebar */
    padding: 20px;
    transition: margin-left 0.3s;
}
.content.collapsed {
    margin-left: 70px;
}
.footer {
    transition: margin-left 0.3s;
    margin-left: 250px; /* Adjust based on the width of the sidebar */
}
.footer.collapsed {
    margin-left: 70px;
}
.toggle-btn {
    background-color: #303438;
    color: white;
    border: none;
    cursor: pointer;
    margin-right: 10px; /* Ajuste a margem conforme necessário */
}
.vertical-nav.collapsed + .toggle-btn {
    left: 80px;
}
.vertical-nav.collapsed > li:first-child {
    margin-top: 20px; /* Adjust this value as needed */
}
```



-------------------
Yii 2 Basic Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.4.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](https://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](https://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer create-project --prefer-dist yiisoft/yii2-app-basic basic
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/web/
~~~

### Install from an Archive File

Extract the archive file downloaded from [yiiframework.com](https://www.yiiframework.com/download/) to
a directory named `basic` that is directly under the Web root.

Set cookie validation key in `config/web.php` file to some random secret string:

```php
'request' => [
    // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
    'cookieValidationKey' => '<secret random string goes here>',
],
```

You can then access the application through the following URL:

~~~
http://localhost/basic/web/
~~~


### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install    
    
Start the container

    docker-compose up -d
    
You can then access the application through the following URL:

    http://127.0.0.1:8000

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](https://codeception.com/).
By default, there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full-featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](https://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2basic_test` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
