drop table if exists users;

create table users (
    id int not null primary key auto_increment,
    email varchar(255) unique,
    username varchar(255) unique,
    password varchar(255),
    created dateTime,
    modified dateTime
);

create table titles (
    id int not null primary key auto_increment,
    title varchar(255),
    filePath varchar(255) unique,
    created dateTime,
    modified dateTime
);

create table nices (
    id int not null primary key auto_increment,
    username varchar(20),
    niceDir varchar(255),
    created dateTime,
    remote_addr varchar(15),
    user_agent varchar(255),
    unique unique_answer (username, niceDir, remote_addr, user_agent)
);

create table profiles (
    id int not null primary key auto_increment,
    username varchar(20) unique,
    intro varchar(255),
    saveFileName varchar(255),
    created dateTime
);

create table comments (
    id int not null primary key auto_increment,
    username varchar(20),
    comment varchar(255),
    filePath varchar(255),
    created dateTime
);