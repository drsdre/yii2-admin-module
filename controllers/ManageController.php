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
    /* @var array */
    public $entity;
    /* @var ActiveRecord */
    public $model;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $entity = Yii::$app->getRequest()->getQueryParam('entity', null);
            $this->entity = $this->getEntity($entity);
            if ($this->entity === null) {
                throw new NotFoundHttpException();
            }

            if (!in_array($action->id, ['index', 'create'])) {
                $id = Yii::$app->getRequest()->getQueryParam('id', null);

                $this->model = $this->loadModel($entity, $id);
                if ($this->model === null) {
                    throw new NotFoundHttpException();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function actionIndex($entity)
    {
        $entity = $this->getEntity($entity);

        $query = call_user_func([$entity->model(), 'find']);
        $modelsProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('index', [
            'entity' => $entity,
            'modelsProvider' => $modelsProvider,
        ]);
    }

    public function actionView()
    {
        return $this->render('view', [
            'entity' => $this->entity,
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
            'entity' => $this->entity,
            'model' => $this->model,
        ]);
    }

    public function actionDelete()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $transaction = Yii::$app->db->beginTransaction();
            $this->model->delete();
            $transaction->commit();

            return $this->redirect(['index', 'item' => $this->entity['id']]);
        }

        return $this->render('delete', [
            'entity' => $this->entity,
            'model' => $this->model,
        ]);
    }

    public function actionCreate()
    {
        $model = Yii::createObject($this->entity['class'], []);

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

            return $this->redirect(['update', 'item' => $this->entity['id'], 'id' => $model->id]);
        }

        return $this->render('create', [
            'entity' => $this->entity,
            'model' => $model,
        ]);
    }
}
