<?php


namespace asdfstudio\admin\controllers;

use asdfstudio\admin\models\ManageForm;
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
            $item = Yii::$app->getRequest()->getQueryParam('item', null);
            $this->item = $this->getItem($item);
            if ($this->item === null) {
                throw new NotFoundHttpException();
            }

            if (!in_array($action->id, ['index', 'create'])) {
                $id = Yii::$app->getRequest()->getQueryParam('id', null);

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
        if (Yii::$app->getRequest()->getIsPost()) {
            $form = new ManageForm([
                'model' => $this->model,
                'data' => Yii::$app->getRequest()->getBodyParams(),
            ]);
            if ($form->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                $form->saveModel();
                $transaction->commit();
            }
        }
        return $this->render('update', [
            'item' => $this->item,
            'model' => $this->model,
        ]);
    }

    public function actionDelete()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $transaction = Yii::$app->db->beginTransaction();
            $this->model->delete();
            $transaction->commit();

            return $this->redirect(['index', 'item' => $this->item->id]);
        }

        return $this->render('delete', [
            'item' => $this->item,
            'model' => $this->model,
        ]);
    }

    public function actionCreate()
    {
        $model = Yii::createObject($this->item->class, []);

        if (Yii::$app->getRequest()->getIsPost()) {
            $form = new ManageForm([
                'model' => $model,
                'data' => Yii::$app->getRequest()->getBodyParams(),
            ]);
            if ($form->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                $form->saveModel();
                $transaction->commit();
            }

            return $this->redirect(['update', 'item' => $this->item->id, 'id' => $model->id]);
        }

        return $this->render('create', [
            'item' => $this->item,
            'model' => $model,
        ]);
    }
}
