DATABASE soporte;

usuarios (
    id ,
    nombre ,
    email ,
    password_hash
);

incidencias (
    id 
    usuario_id ,
    asunto ,
    descripcion,
    estado,
    fecha_creacion 
);
