use yeticave;

/* добавление категорий */
# insert into categories (id, name) VALUES ('boards', 'Доски и лыжи'),
#                                          ('fasting','Крепления'),
#                                          ('boots','Ботинки'),
#                                          ('clothing', 'Одежда'),
#                                          ('tools', 'Инструменты'),
#                                          ('other', 'Разное');
/*Добавляем user'ов для обкатки*/
# insert into users (name, email, img, password) VALUES ('Игнат', 'ignat.v@gmail.com', 'img/user.jpg', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
#                                                       ('Леночка', 'kitty_93@li.ru', 'img/user.jpg', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'),
#                                                       ('Руслан', 'warrior07@mail.ru', 'img/user.jpg', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');

/*Добавляем существующие объявления*/
# insert into lots (title, description, img, add_date, expire_date, category_id, user_id, start_price, step, curr_price)
# VALUES ('2014 Rossignol District Snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', 'img/lots/lot-1.jpg', 1570775006, 1760163806, 'boards', 1, 10999, 1000, 10999),
#        ('DC Ply men 2016/2017 Snowboard', 'Отличный варик для тех, кто может себе позволить борд по цене LADA 2114', 'img/lots/lot-2.jpg', 1570602206, 1760163806, 'boards', 2, 159999, 5000, 159999),
#        ('Крепления Union Contact Pro 2015 года размер L/XL', 'Держат так крепко, что даже медведь не вырвет вас из борда. Придётся есть так.', 'img/lots/lot-3.jpg', 1570775006, 1760163806, 'fasting', 3, 8000, 100, 8000),
#        ('Ботинки для сноуборда DC Mutiny Charcoal', 'В принципе, в них можно ходить и на работу.', 'img/lots/lot-4.jpg', 1570775006, 1760163806, 'boots', 1, 10999, 1000, 10999),
#        ('Куртка для сноуборда DC Mutiny Charcoal', 'В этой куртяхе тебя не продует даже на скорости 300км/ч!', 'img/lots/lot-5.jpg', 1570602206, 1760163806, 'clothing', 2, 7500, 100, 7500),
#        ('Маска Oakley Canopy', 'Это не просто маска! Это настоящая находка для тех, кто не хочет, чтобы его глаза обледенели. Потому что она с подогревом.', 'img/lots/lot-6.jpg', 1570775006, 1760163806, 'other', 3, 5400, 100, 5400);
# /*Сделаем немного ставок для одного из объявлений */
# insert into bets (add_date, price, lot_id, user_id)
# VALUES (1570775006, 11200, 1, 1),
#        (1570775006, 11300, 1, 2),
#        (1570775006, 11400, 1, 3);
/*Получить все категории*/
select name from categories;
/*Получить самые новые объявления*/
select title, start_price, img, curr_price, category_id from lots l
join categories c on l.category_id = c.id
where add_date >= 1570775006 and winner_id is null;
/*Получить лот по id*/
SELECT l.id, title, description, img, curr_price, c.name FROM lots l
JOIN categories c ON l.category_id = c.id
ORDER BY add_date DESC;

# insert into lots (title, description, img, add_date, expire_date, category_id, user_id, curr_price, step, start_price)
# VALUES ('Bla bla super premium', 'Невероятно хороший борд, который можно взять с собой в кармашек.', 'img/lots/test_board.jpg', 1571293406, 1602915806, 'boards', 6, 32000, 1500, 32000);

