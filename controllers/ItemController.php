<?php
class ItemController extends Controller
{
    public function actionIndex()
    {
        $model = new Item('search');
        if(isset($_GET['Item'])) $model->attributes = $_GET['Item'];
        $this->render('index', ['model'=>$model]);
    }

    public function actionView($id){ $this->render('view', ['model'=> $this->loadModel($id)]); }

    public function actionCreate(){
        $model = new Item();
        if(isset($_POST['Item'])){
            $model->attributes = $_POST['Item'];
            if($model->save()){
                $this->redirect(['view','id'=>$model->id]);
            }
        }
        $this->render('create', ['model'=>$model]);
    }

    public function actionUpdate($id){
        $model = $this->loadModel($id);
        if(isset($_POST['Item'])){
            $model->attributes = $_POST['Item'];
            if($model->save()){
                $this->redirect(['view','id'=>$model->id]);
            }
        }
        $this->render('update', ['model'=>$model]);
    }

    public function actionDelete($id){
        if(Yii::app()->request->isPostRequest){
            $this->loadModel($id)->delete();
            if(!isset($_GET['ajax'])) $this->redirect(['index']);
        } else throw new CHttpException(400,'Invalid request.');
    }

    protected function loadModel($id){
        $m = Item::model()->findByPk($id);
        if($m===null) throw new CHttpException(404,'Not found.');
        return $m;
    }
}
