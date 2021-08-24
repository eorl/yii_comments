<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\CommentForm */
/* @var $comments array */

use kartik\datetime\DateTimePicker;
use kartik\icons\FontAwesomeAsset;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'My Yii Application';
FontAwesomeAsset::register($this);
?>
<div class="site-index">

    <div class="body-content" id="comments-list">
        <?php if (Yii::$app->user->isGuest): ?>
            <?php foreach ($comments as $comment): ?>
            <div class="row">
                <div class="col">
                    <p><?=$comment->content?></p>
                    <p><i><?=$comment->created_at?> - <b><?=$comment->author?></b></i></p>
                </div>
            </div>
            <?php endforeach;?>
        <?php endif; ?>

    </div>
    <?php if (Yii::$app->user->isGuest): ?>
        <?php if (Yii::$app->session->hasFlash('commentFormSubmitted')): ?>

            <div class="alert alert-success">
                Thank you for comment.
            </div>

        <?php endif; ?>

        <p>
            Please, comment.
        </p>
    <?php endif;?>
    <div class="row <?=(Yii::$app->user->isGuest ?: 'd-none')?>" id="form-row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'comment-form']); ?>

            <?php if (!Yii::$app->user->isGuest): ?>
                <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
            <?php endif;?>

            <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

            <?php if (!Yii::$app->user->isGuest): ?>
                <?= $form->field($model, 'created_at')->widget(DateTimePicker::class, ['bsVersion' => 4, ]) ?>
            <?php endif;?>

            <?= $form->field($model, 'author')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'comment-button', 'value' => 'save']) ?>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::submitButton('Delete', ['class' => 'btn btn-primary', 'name' => 'comment-button', 'value' => 'delete']) ?>
                <?php endif;?>

            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>

<?php if (!Yii::$app->user->isGuest): ?>
    <?php $this->registerJs('
        function addData(id, content, created_at, author){
            cloneRow = $("#form-row").clone(true);
            cloneRow.removeAttr("id");
            cloneRow.removeClass("d-none");
            cloneRow.find("[id]").each(function() {
                attr = $(this).attr("id")+"_"+id;
                $(this).attr("id", attr)
            });
            $("#comments-list").append(cloneRow);
            $("#commentform-created_at_"+id).datetimepicker("remove");
            $("#commentform-created_at_"+id).val(created_at);
            $("#commentform-created_at_"+id).datetimepicker();
            $("#commentform-id_"+id).val(id);
            $("#commentform-content_"+id).val(content);
            $("#commentform-author_"+id).val(author);
        }
            
        $(function(){
            function wsStart() {
                ws = new WebSocket("'. Yii::$app->params['websocket'] .'");
                ws.onopen = function() { console.log("system: connection is open");};
                ws.onclose = function() { console.log("system: the connection is closed, I try to reconnect"); setTimeout(wsStart, 1000);};
                ws.onmessage = function(evt) { 
                    obj = JSON.parse(evt.data);
                    addData(obj.id, obj.content, obj.created_at, obj.author); 
                    console.log(evt.data);
                };
            }
    
            wsStart();     
        });
    ');?>
    <?php foreach ($comments as $comment): ?>
        <?php $this->registerJs('addData("'. $comment->id .'", "'. $comment->content .'", "'. $comment->created_at .'", "'. $comment->author .'");'); ?>
    <?php endforeach; ?>
<?php endif;?>
