use yeticave;

create table users (
    id smallint auto_increment primary key,
    reg_date int,
    name char(64),
    email char(64),
    img char(128),
    password char(60),
    contacts char(255)
);
create unique index email on users(email);
create unique index id on users(id);

create table categories (
    id char(10) primary key,
    name char(15)
);
create unique index id on categories(id);

create table lots (
    id smallint auto_increment primary key,
    title char(128),
    description text,
    img char(128),
    add_date int,
    expire_date int,
    category_id char(10),
    user_id smallint,
    price mediumint,
    step smallint,
    winner_id smallint
);
create unique index id on lots(id);
create index price on lots(price);
create index step on lots(step);
create index category_id on lots(category_id);
create index user_id on lots(user_id);
create index winner_id on lots(winner_id);
create index add_date on lots(add_date);
create index expire_date on lots(expire_date);

create table bets (
    id smallint auto_increment primary key,
    add_date int,
    price mediumint,
    lot_id smallint,
    user_id smallint
);
create unique index id on bets(id);
create index price on bets(price);
create index lot_id on bets(lot_id);
create index user_id on bets(user_id);
