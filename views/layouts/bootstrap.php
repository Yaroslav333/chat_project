<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- NavBar -->
<div class="navbar-wrapper">
    <div class="container">
        <div class="navbar navbar-inverse navbar-static-top">


            <?php

            NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Главная', 'url' => ['/main/index']],
                    ['label' => 'Регистрация', 'url' => ['/main/register']],

                     Yii::$app->user->isGuest ? (
                    ['label' => 'Вход', 'url' => ['/main/login']]
                    ) : (
                        '<li>'
                        . Html::beginForm(['/main/logout'], 'post')
                        . Html::submitButton(
                            'Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link logout']

                        )
                        . Html::endForm()
                        . '</li>'
                    )


                ],
            ]);
            NavBar::end();

            ?>




            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/main/index">Главная</a></li>
                    <li><a href="/main/register">Регистрация</a></li>
                    <li><a href="/main/login">Войти</a></li>

                </ul>
            </div>

        </div>
    </div><!-- /container -->
</div><!-- /navbar wrapper -->

<?= $content ?>




<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
