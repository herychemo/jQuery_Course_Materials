
create database if not EXISTS aux;
use aux;
drop database IF EXISTS usuarios_jquery;
create database usuarios_jquery;
use usuarios_jquery;

create table usuarios(
	id integer primary key auto_increment,
	user varchar(50) not null,
	fullName varchar(50) not null,
	description varchar(200) not null,
	result boolean not null
);
