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