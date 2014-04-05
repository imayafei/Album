/**
 * 创建数据库 album
 */
create database album;

use album;

/**
 * 1.创建 users 表
 * userid 用户ID
 * username 用户名
 * password 用户密码
 */
create table users(
	userid mediumint unsigned not null primary key auto_increment,
	username char(15) not null,
	email varchar(30) not null,
	password char(32) not null
)engine='myisam';
