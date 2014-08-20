<?php


namespace asdfstudio\admin\controllers;

use Yii;
use asdfstudio\admin\models\Item;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Class ManageController
 * @package asdfstudio\admin\controllers
 */
class ManageController extends Controller
{
    /* @var Item */
    public $item;
    /* @var ActiveRecord */
    public $model;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!in_array($action->id, ['index'])) {
                $id = Yii::$app->getRequest()->getQueryParam('id', null);
                $item = Yii::$app->getRequest()->getQueryParam('item', null);

                $this->item = $this->getItem($item);
                if ($this->item === null) {
                    throw new NotFoundHttpException();
                }
                $this->model = $this->loadModel($item, $id);
                if ($this->model === null) {
                    throw new NotFoundHttpException();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function actionIndex($item)
    {
        /* @var Item|string $item */
        $item = $this->module->items[$item];

        $query = call_user_func([$item->class, 'find']);
        $modelsProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('index', [
            'item' => $item,
            'modelsProvider' => $modelsProvider,
        ]);
    }

    public function actionView()
    {
        return $this->render('view', [
            'item' => $this->item,
            'model' => $this->model,
        ]);
    }

    public function actionUpdate()
    {
        return $this->render('update', [
            'item' => $this->item,
            'model' => $this->model,
        ]);
    }

    public function actionDelete($item, $id)
    {

    }
}
