
    <form class="form form--add-lot container <?=$class = $error_list ? $error_list['form_class'] : '';?>" action="../add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?=$class = $error_list['lot-name'] ? $error_list['input_class'] : '';?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота." value="<?=$value = $error_savings['lot-name'] ? $error_savings['lot-name'] : '';?>" required>
                <span class="form__error">Введите наименование лота</span>
            </div>
            <div class="form__item <?=$class = $error_list['category'] ? $error_list['input_class'] : '';?>">
                <label for="category">Категория</label>
                <select id="category" name="category" required>
                    <option <?php if ($error_savings and $error_savings['catErr'] == 'Выберите категорию') {print('selected');} else {print('');}; ?>>Выберите категорию</option>
                    <option <?php if ($error_savings and $error_savings['catErr'] == 'Доски и лыжи') {print('selected');} else {print('');}; ?>>Доски и лыжи</option>
                    <option <?php if ($error_savings and $error_savings['catErr'] == 'Крепления') {print('selected');} else {print('');}; ?>>Крепления</option>
                    <option <?php if ($error_savings and $error_savings['catErr'] =='Ботинки') {print('selected');} else {print('');}; ?>>Ботинки</option>
                    <option <?php if ($error_savings and $error_savings['catErr'] == 'Одежда') {print('selected');} else {print('');}; ?>>Одежда</option>
                    <option <?php if ($error_savings and $error_savings['catErr'] == 'Инструменты') {print('selected');} else {print('');}; ?>>Инструменты</option>
                    <option <?php if ($error_savings and $error_savings['catErr'] == 'Разное') {print('selected');} else {print('');}; ?>>Разное</option>
                </select>
                <span class="form__error">Выберите категорию</span>
            </div>
        </div>
        <div class="form__item form__item--wide <?=$class = $error_list['message'] ? $error_list['input_class'] : '';?>">
            <label for="message">Описание</label>
            <textarea id="message" name="message" placeholder="Напишите описание лота" required><?php if ($error_savings) {print($error_savings['message']);}; ?></textarea>
            <span class="form__error">Напишите описание лота</span>
        </div>
        <div class="form__item form__item--file <?=$class = $error_list['lot-photo'] ? $error_list['input_class'] : '';?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="lot-photo" id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?=$class = $error_list['lot-rate'] ? $error_list['input_class'] : '';?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="lot-rate" placeholder="0" value="<?=$value = $error_savings['lot-rate'] ? $error_savings['lot-rate'] : '';?>" required>
                <span class="form__error">Введите начальную цену</span>
            </div>
            <div class="form__item form__item--small <?=$class = $error_list['lot-step'] ? $error_list['input_class'] : '';?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="lot-step" placeholder="0" value="<?=$value = $error_savings['lot-step'] ? $error_savings['lot-step'] : '';?>" required>
                <span class="form__error">Введите шаг ставки</span>
            </div>
            <div class="form__item <?=$class = $error_list['lot-date'] ? $error_list['input_class'] : '';?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="lot-date" value="<?=$value = $error_savings['lot-date'] ? $error_savings['lot-date'] : '';?>" required>
                <span class="form__error">Введите дату завершения торгов</span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <?php if ($error_list) {
            foreach ($error_list as $key => $value) {
                if ($error_list[$key] != 'form--invalid' and $error_list[$key] != 'form__item--invalid') {
                    print('<p style="width: auto; color: red">' . $error_list[$key] . '</p>');
                }
            }
        };?>
        <button type="submit" class="button" name="lot-submit">Добавить лот</button>
    </form>
