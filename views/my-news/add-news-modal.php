<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $model \app\models\form\LoginForm */
?>
<div class="modal-dialog">
    <div class="modal-content col-lg-12">
        <div class="modal-header">
            <h4>
                Add news
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </h4>
        </div>
        <div class="modal-body modal-body-white col-lg-12">
            <?php $form = ActiveForm::begin([
                'id' => 'add-news-form',
                'enableClientValidation' => true,
                'validateOnBlur' => false,
                'validateOnChange' => false,
                'options' => ['enctype' => 'multipart/form-data']
            ])?>
            <div class="col-lg-12">
                <?= $form->field($model, 'title', [
                    'template' => '{label}{input}<div class="col-lg-3"></div>{error}'
                ])->textInput([
                    'class' => 'col-lg-9',
                ])->label('Title', [
                    'class' => 'col-lg-3 label-text-right'
                ])?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'content', [
                    'template' => '{label}{input}<div class="col-lg-3"></div>{error}'
                ])->textarea([
                    'class' => 'col-lg-9'
                ])->label('Content', [
                    'class' => 'col-lg-3 label-text-right'
                ])?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
            </div>
            <div class="modal-footer col-lg-12">
                <?php
                echo Html::a('Cancel', 'javascript:void(0)', ['class' => 'btn', 'data-dismiss' => 'modal']);
                echo Html::submitButton('Save', [
                    'class' => 'btn btn-primary',
                ]);
                ?>
            </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
</div>