create table post
(
    id      integer not null
        constraint post_pk
            primary key autoincrement,
    subject text not null,
    content text not null
);
create table tea
(
    id      integer not null
        constraint tea_pk
            primary key autoincrement,
    name text not null,
    type text not null,
    description text not null
);
