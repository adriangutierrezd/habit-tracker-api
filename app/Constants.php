<?php

namespace App;

class Constants
{

    const HTTP_OK_CODE = 200;
    const HTTP_CREATED_CODE = 201;
    const HTTP_FORBIDDEN_CODE = 403;
    const HTTP_NOT_FOUND_CODE = 404;
    const HTTP_UNAUTHORIZED_CODE = 401;
    const HTTP_BAD_REQUEST_CODE = 400;
    const HTTP_SERVER_ERROR_CODE = 500;


    const HTTP_UPDATED_MSG = 'Recurso actualizado con éxito';
    const HTTP_CREATED_MSG = 'Recurso creado con éxito';
    const HTTP_FETCHING_MSG = 'Datos obtenidos correctamente';
    const HTTP_UNAUTHORIZED_MSG = 'No autorizado';
    const HTTP_FORBIDDEN_MSG = 'No tienes permisos para esto';
    const HTTP_NOT_FOUND_MSG = 'Recurso no encontrado';
    const HTTP_SERVER_ERROR_MSG = 'Ha ocurrido un error inesperado';
    const HTTP_BAD_REQUEST_MSG = 'Solicitud errónea';
    const HTTP_AUTH_ERROR_MSG = 'Credenciales inválidas';
}
