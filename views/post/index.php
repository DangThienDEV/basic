<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $posts app\models\Post[] */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= Html::encode($post->id) ?></td>
                    <td><?= Html::encode($post->title) ?></td>
                    <td><?= Html::encode($post->content) ?></td>
                    <td><?= Html::encode(date('Y-m-d H:i:s', $post->created_at)) ?></td>
                    <td><?= Html::encode(date('Y-m-d H:i:s', $post->updated_at)) ?></td>
                    <td>
                        <?= Html::a('Update', ['update', 'id' => $post->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $post->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
