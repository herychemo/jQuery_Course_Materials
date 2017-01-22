
create database if not EXISTS aux;
use aux;
drop database IF EXISTS jQuery_project;
create database jQuery_project;
use jQuery_project;

create table msgs(
	id integer primary key auto_increment,
	msg varchar( 230 ) not null,
	uri varchar( 30 ) not null ,
	user varchar(20) not null,
	ip	varchar(16) not null,
	time_ TIME not null
);
