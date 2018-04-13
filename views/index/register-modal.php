<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $model \app\models\form\RegisterForm */
?>
<div class="modal-dialog">
    <div class="modal-content col-lg-12">
        <div class="modal-header">
            <h4>
                Register
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </h4>
        </div>
        <div class="modal-body modal-body-white col-lg-12" style="overflow-y:auto; overflow-x:hidden;">
            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
            ])?>
            <div class="col-lg-12">
                <?= $form->field($model, 'email', [
                    'template' => '{label}{input}<div class="col-lg-3"></div>{error}'
                ])->textInput([
                    'class' => 'col-lg-9'
                ])->label('Email', [
                    'class' => 'col-lg-3 label-text-right'
                ])?>
            </div>
            <div class="modal-footer col-lg-12">
                <?php
                echo Html::a('Cancel', 'javascript:void(0)', ['class' => 'btn', 'data-dismiss' => 'modal']);
                echo Html::submitButton('Register', [
                    'class' => 'btn btn-primary',
                ]);
                ?>
            </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
</div>