<?php


namespace asdfstudio\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use asdfstudio\admin\base\Entity;
use asdfstudio\admin\forms\Form;

/**
 * Class ManageController
 * @package asdfstudio\admin\controllers
 */
class ManageController extends Controller
{
    /* @var Entity */
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
            /* @var Form $form */
            $form = Yii::createObject(ArrayHelper::merge([
                'model' => $this->model,
            ], $this->entity->form('update')));

            $form->model->load(Yii::$app->getRequest()->getBodyParams());
            if ($form->model->validate()) {
                $form->saveModel($this->entity->attributes);
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

            return $this->redirect(['index', 'item' => $this->entity->id]);
        }

        return $this->render('delete', [
            'entity' => $this->entity,
            'model' => $this->model,
        ]);
    }

    public function actionCreate()
    {
        $model = Yii::createObject($this->entity->model(), []);
        if (Yii::$app->getRequest()->getIsPost()) {
            /* @var Form $form */
            $form = Yii::createObject(ArrayHelper::merge([
                'model' => $model,
            ], $this->entity->form('create')));

            $form->model->load(Yii::$app->getRequest()->getBodyParams());
            if ($form->model->validate()) {
                if ($form->saveModel($this->entity->attributes)) {
                    return $this->redirect([
                        'update',
                        'entity' => $this->entity->id,
                        'id' => $form->model->primaryKey,
                    ]);
                }
            }
        }

        return $this->render('create', [
            'entity' => $this->entity,
            'model' => $model,
        ]);
    }
}
