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
