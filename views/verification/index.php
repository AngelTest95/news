<?php
/* @var $model \app\models\form\VerificationForm */
/* @var boolean $isValid */
/* @var string $message */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="well"><?=$message?></div>
<?php if ($isValid) {?>
    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
    ])?>
    <div class="col-lg-12">
        <?= $form->field($model, 'username', [
            'template' => '{label}{input}<div class="col-lg-3"></div>{error}'
        ])->textInput([
            'class' => 'col-lg-9',
        ])->label('Username', [
            'class' => 'col-lg-3 label-text-right'
        ])?>
    </div>
    <div class="col-lg-12">
        <?= $form->field($model, 'password', [
            'template' => '{label}{input}<div class="col-lg-3"></div>{error}'
        ])->input('password', [
            'class' => 'col-lg-9'
        ])->label('Password', [
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
<?php }?>
