
<form class="form container <?php if ($error_list and $error_list['form_invalid']) print($error_list['form_invalid']); ?>" action="login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?php if ($error_list and $error_list['email']) {print($error_list['class_invalid']);};?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?php if ($error_list) {print($error_vars['email']);} ;?>" required>
        <span class="form__error">Введите e-mail</span>
    </div>
    <div class="form__item <?php if ($error_list and $error_list['password']) {print($error_list['class_invalid']);}; ?> form__item--last">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль" required>
        <span class="form__error">Введите пароль</span>
    </div>
    <button type="submit" class="button">Войти</button>
    <?php if ($error_list) :?>
    <div class="form__item">
        <?php foreach ($error_list as $key => $value):?>
        <?php if ($key != 'class_invalid' and $key != 'form_invalid') :?>
        <p style="color: red"><?=$error_list[$key];?></p>
        <?php endif;?>
        <?php endforeach;?>
    </div>
    <?php endif;?>
</form>
