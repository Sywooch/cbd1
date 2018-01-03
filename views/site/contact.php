<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app','Contact');
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="../css/ol.css" type="text/css">
<script src="../js/ol.js"></script>

<div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-phone-alt"></span><strong> Зв’язатися з нами</strong></div>
    <div class="panel-body">

        <div class="site-contact">

            <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

                <div class="alert alert-success">
                    Thank you for contacting us. We will respond to you as soon as possible.
                </div>

                <p>
                    Note that if you turn on the Yii debugger, you should be able
                    to view the mail message on the mail panel of the debugger.
                    <?php if (Yii::$app->mailer->useFileTransport): ?>
                        Because the application is in development mode, the email is not sent but saved as
                        a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                        Please configure the <code>useFileTransport</code> property of the <code>mail</code>
                        application component to be false to enable email sending.
                    <?php endif; ?>
                </p>

            <?php else: ?>


                <div class="row">
                    <div class="col-lg-6">
                      <address><h4>
                        <strong>:: ReactLogic Agency ::</strong><br><br>
                        Україна,<br> 36011, м. Полтава,<br>
                        вул. Конева 4/2
                      </address>

                      <address><h4>
                        
                        <strong>телефоны:</strong><a href="tel:+380500000000"> 0500000000</a>, <a href="tel:+380500000000"> 0500000000</a>
                        <p><br /></p>
                        <strong>електронна пошта:</strong><a href="mailto:admin@react-logic.com"> admin@react-logic.com</a>
                        <p><br /></p>
                        
                      </address>

                        <?php /*$form = ActiveForm::begin(['id' => 'contact-form']); ?>

                            <?= $form->field($model, 'name') ?>

                            <?= $form->field($model, 'email') ?>

                            <?= $form->field($model, 'subject') ?>

                            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

                            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                            ]) ?>

                            <div class="form-group">
                                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                            </div>

                        <?php ActiveForm::end(); */?>

                    </div>
                    <div  class="col-lg-6">


                        <div class="container-fluid">

                        <div class="row-fluid">
                          <div class="span12">
                            <div id="map" class="map"></div>
                            <div id="marker" title="Marker"></div>
                          </div>
                        </div>

                        </div>
                        <script>
                        var attribution = new ol.control.Attribution({
                          collapsible: false
                        });
                        var map = new ol.Map({
                          layers: [
                            new ol.layer.Tile({
                              source: new ol.source.OSM()
                            })
                          ],
                          controls: ol.control.defaults({ attribution: false }).extend([attribution]),
                          target: 'map',
                          view: new ol.View({
                            projection: 'EPSG:3857',
                            center: ol.proj.transform([34.507666, 49.574596], // < больше - в лево
                                'EPSG:4326', 'EPSG:3857'),
                            zoom: 16,
                            maxZoom: 18,
                            minZoom: 12,

                          })
                        });

                        // Disposition marker
                        var marker = new ol.Overlay({
                          position: ol.proj.fromLonLat([34.507666, 49.574596]), //49.586726, 34.551578
                          positioning: 'center-center',
                          element: document.getElementById('marker'),
                          stopEvent: false
                        });

                        map.addOverlay(marker);

                        function checkSize() {
                          var small = map.getSize()[0] < 600;
                          attribution.setCollapsible(small);
                          attribution.setCollapsed(small);
                        }

                        window.addEventListener('resize', checkSize);
                        checkSize();

                        </script>

                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

