
<form class="form container <? if ($error_list) {print($error_list["form_class"]);};?>" action="sign-up.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <? if ($error_list["email"]) {print($error_list["input_class"]);};?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<? if ($user_savings["email"]) {print($user_savings["email"]);};?>" required>
        <span class="form__error">Введите e-mail</span>
    </div>
    <div class="form__item <? if ($error_list["password"]) {print($error_list["input_class"]);};?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль" required>
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item  <? if ($error_list["name"]) {print($error_list["input_class"]);};?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<? if ($user_savings["name"]) {print($user_savings["name"]);};?>" required>
        <span class="form__error">Введите имя</span>
    </div>
    <div class="form__item  <? if ($error_list["message"]) {print($error_list["input_class"]);};?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required><? if ($user_savings["message"]) {print($user_savings["message"]);};?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>
    <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="user-avatar">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <? if ($error_list) :?>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <? foreach ($error_list as $key => $value) : ?>
    <?php if ($key != "form_class" and $key != "input_class") : ?>
        <p><?=$error_list[$key];?></p>
    <?php endif;?>
    <? endforeach;?>
    <? endif;?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
