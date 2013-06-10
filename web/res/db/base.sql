BEGIN;
CREATE TABLE "USUARIO" (
    "id" integer NOT NULL PRIMARY KEY,
    "nombre" varchar(100) NOT NULL,
    "passwd" varchar(150) NOT NULL
)
;
CREATE TABLE "PROYECTO_miembros" (
    "id" integer NOT NULL PRIMARY KEY,
    "proyecto_id" integer NOT NULL,
    "usuario_id" integer NOT NULL REFERENCES "USUARIO" ("id"),
    UNIQUE ("proyecto_id", "usuario_id")
)
;
CREATE TABLE "PROYECTO" (
    "id" integer NOT NULL PRIMARY KEY,
    "nombre" varchar(100) NOT NULL,
    "descripcion" text NOT NULL
)
;


CREATE TABLE "tarea" (
    "id" integer NOT NULL PRIMARY KEY,
    "nombre" varchar(140) NOT NULL,
    "descripcion" varchar(250) NOT NULL,
    "usuario_creador_id" integer NOT NULL REFERENCES "USUARIO" ("id"),
    "proyecto_id" integer NOT NULL REFERENCES "PROYECTO" ("id"),
    "fecha_creacion" date NOT NULL,
    "fecha_limite" date NOT NULL,
    "urgencia" smallint unsigned NOT NULL
)
;
CREATE TABLE "tarea_paso" (
    "id" integer NOT NULL PRIMARY KEY,
    "nombre" varchar(70) NOT NULL,
    "tarea_id" integer NOT NULL REFERENCES "tarea" ("id")
)
;
CREATE TABLE "EVENTO_asistentes" (
    "id" integer NOT NULL PRIMARY KEY,
    "evento_id" integer NOT NULL,
    "usuario_id" integer NOT NULL REFERENCES "USUARIO" ("id"),
    UNIQUE ("evento_id", "usuario_id")
)
;
CREATE TABLE "EVENTO" (
    "id" integer NOT NULL PRIMARY KEY,
    "nombre" varchar(140) NOT NULL,
    "descripcion" varchar(250) NOT NULL,
    "usuario_creador_id" integer NOT NULL REFERENCES "USUARIO" ("id"),
    "proyecto_id" integer NOT NULL REFERENCES "PROYECTO" ("id"),
    "fecha_creacion" date NOT NULL,
    "fecha_limite" date NOT NULL,
    "lugar" varchar(150) NOT NULL,
    "fecha_hora" datetime NOT NULL
)
;
CREATE TABLE "DISCUSION" (
    "id" integer NOT NULL PRIMARY KEY,
    "nombre" varchar(140) NOT NULL,
    "descripcion" varchar(250) NOT NULL,
    "usuario_creador_id" integer NOT NULL REFERENCES "USUARIO" ("id"),
    "proyecto_id" integer NOT NULL REFERENCES "PROYECTO" ("id"),
    "fecha_creacion" date NOT NULL,
    "fecha_limite" date NOT NULL,
    "categoria" varchar(140) NOT NULL
)
;
CREATE TABLE "COMENTARIO" (
    "id" integer NOT NULL PRIMARY KEY,
    "usuario_id" integer NOT NULL REFERENCES "USUARIO" ("id"),
    "hilo_de_id" integer NOT NULL,
    "discusion_id" integer NOT NULL REFERENCES "DISCUSION" ("id")
)
;
CREATE TABLE "DOCUMENTOS" (
    "id" integer NOT NULL PRIMARY KEY,
    "nombre" varchar(140) NOT NULL,
    "descripcion" varchar(250) NOT NULL,
    "usuario_creador_id" integer NOT NULL REFERENCES "USUARIO" ("id"),
    "proyecto_id" integer NOT NULL REFERENCES "PROYECTO" ("id"),
    "fecha_creacion" date NOT NULL,
    "fecha_limite" date NOT NULL,
    "directorio" varchar(100) NOT NULL
)
;

COMMIT;