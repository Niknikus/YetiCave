<?php
require_once "functions.php";
?>
    <div class="container">
        <section class="lots">
            <h2>История просмотров</span></h2>
            <ul class="lots__list">
                <?php foreach ($lots as $key => $value) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$lots[$key]['img'];?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$lots[$key]['name'];?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lots[$key]['id'];?>"><?=$lots[$key]['title'];?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=$lots[$key]['curr_price'];?><b class="rub">р</b></span>
                            </div>
                            <div class="lot__timer timer">
                                <?=expire_lotTime($lots[$key]["expire_date"]);?>
                            </div>
                        </div>
                    </div>
                </li>
                <? endforeach;?>
            </ul>
        </section>
        <?php if (count($lots) > 6) :?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item"><a href="#">3</a></li>
            <li class="pagination-item"><a href="#">4</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
        <?php endif;?>
    </div>
