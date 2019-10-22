<?php
require_once 'functions.php';
?>
    <section class="lot-item container">
        <h2><?=$lot['title']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?=$lot['img']; ?>" width="730" height="548" alt="<?=$lot['title'];?>">
                </div>
                <p class="lot-item__category">Категория: <span><?=$lot['name']; ?></span></p>
                <p class="lot-item__description"><?=$lot['description'];?></p>
            </div><?php if ($_SESSION['is_auth']) : ?>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?=expire_lotTime($lot["expire_date"]);?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=price_format($lot["curr_price"]); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?=price_format($lot["curr_price"] + $lot["step"]);?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="bets.php?id=<?=$_GET["id"];?>" method="post">
                        <p class="lot-item__form-item">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="number" name="cost" placeholder="<?=price_format($lot["curr_price"] + $lot["step"]);?>" min="<?=$lot["curr_price"] + $lot["step"];?>">
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div><?php endif;?>
                <div class="history">
                    <h3>История ставок (<span><?=count($bets);?></span>)</h3>
                    <?php if ($bets and count($bets) > 0) :?>
                    <table class="history__list">
                        <?php foreach ($bets as $items) :?>
                        <tr class="history__item">
                            <td class="history__name"><?=$items["name"];?></td>
                            <td class="history__price"><?=price_format($items["price"]);?></td>
                            <td class="history__time"><?=time_from_bet($items["add_date"]);?></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                    <?php else:?>
                        <div class="history">
                            <p>Пока нет ставок.</p>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </section>


