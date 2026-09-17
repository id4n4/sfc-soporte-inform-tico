create database if not exists soporte

use soporte

create table usuarios (
	id int auto_increment primary key,
	nombre varchar(100) not null,
	email varchar(50) not null,
	password_hash varchar(255) not null
)

create table incidencias (
    id int auto_increment primary key,
    usuario_id int not null  ,
    asunto varchar(150) not null,
    descripcion text not null,
    estado varchar(150) not null,
    fecha_creacion datetime not null default current_timestamp,
    foreign key (usuario_id) references usuarios(id)
)
