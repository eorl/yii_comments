<?php

namespace app\models;

use app\models\Comment;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CommentForm extends Model
{
    public $id;
    public $content;
    public $author;
    public $created_at;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['author', 'content'], 'required'],
            [['id', 'created_at'], 'default'],
        ];
    }

    /**
     * Adds a comment
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $comment = false;
            if (!Yii::$app->user->isGuest && $this->id) {
                $comment = Comment::findOne($this->id);
            }
            if (!$comment) {
                $comment = new Comment();
            }
            if (!Yii::$app->user->isGuest) {
                $comment->created_at = $this->created_at;
            }
            $comment->author = $this->author;
            $comment->content = $this->content;
            $comment->save();
            $comment->refresh();

            try {
                $localsocket = Yii::$app->params['localsocket'];
                $instance = stream_socket_client($localsocket, $errno, $errstr);

                fwrite($instance, json_encode(['message' => json_encode($comment->getAttributes())]) . "\n");
            } finally {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes a comment
     * @return bool
     */
    public function delete()
    {
        if (!Yii::$app->user->isGuest && $this->id) {
            if ($comment = Comment::findOne($this->id)) {
                $comment->delete();
                return true;
            }
        }
        return false;
    }
}
