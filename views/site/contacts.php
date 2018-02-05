<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<section class="contacts">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="content-title text-uppercase">Контакти</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <h3 class="contacts__title">Наші реквізити:</h3>

            <div class="contacts__info">
                <p class="contacts__text">р\р &lrm;26007210398859 в АТ "ПроКредит Банк",</p>
                <p class="contacts__text">МФО 320984, КОД ЕДРПОУ &lrm;41500048,</p>
                <p class="contacts__text">ІПН &lrm;415000426543</p>
                <h4 class="contacts__subtitle">Адреса:</h4>
                <p class="contacts__text">04080, м.Київ, вул.Новокостянтинівська 2А, оф 402</p>
                <h4 class="contacts__subtitle">Email:</h4>
                <p class="contacts__text">office@biddingtime.com.ua</p>
                <h4 class="contacts__subtitle">Телефон:</h4>
                <p class="contacts__text">+38 044 351 10 81</p>
            </div>

        </div>
        <div class="col-xs-12 col-md-offset-1 col-md-7">
            <h3 class="contacts__title">Зворотній зв’язок:</h3>
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'form'],
                ]);?>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                                <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-user"></span>
                              </span>
                            <?= $form->field($model, 'name', ['template' => "{label}\n{input}\n{hint}"])->textInput([
                                'class' => 'form-control form__field',
                                'placeholder' => Yii::t('app', 'Ваше ім\'я'),
                            ])->label(false); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-envelope"></span>
                          </span>
                            <?= $form->field($model, 'email', ['template' => "{label}\n{input}\n{hint}"])->textInput([
                                'class' => 'form-control form__field',
                                'placeholder' => Yii::t('app', 'Ваш email')
                            ])->label(false)?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <?= $form->field($model, 'text')->textarea([
                            'class' => 'form-control',
                            'placeholder' => Yii::t('app', 'Ваше повідомлення')
                        ])->label(false)?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <?= Html::submitButton(Yii::t('app', 'Відправити'), ['class' => 'btn form__button'])?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</section>
<section class="map">
    <div class="row">
        <div class="col-xs-12">
            <div class="map__image">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2539.0100890753047!2d30.492166451536402!3d50.47815589314802!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4ce0a101726f3%3A0x556d9ba952603a10!2z0LLRg9C70LjRhtGPINCd0L7QstC-0LrQvtGB0YLRj9C90YLQuNC90ZbQstGB0YzQutCwLCAyLCDQmtC40ZfQsg!5e0!3m2!1sru!2sua!4v1504268968575" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>
