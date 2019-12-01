# API REST FUTBOL

## Instalación
```cmd
git clone https://github.com/sagitarius29/laravel-api-test.git
cd laravel-api-test
composer install
php artisan key:generate
cp .env.example .env
php artisan migrate --seed
```

Modificar el archivo .env para ingresar el token
```text
FOOTBALL_API_TOKEN=
FOOTBALL_API_PER_MINUTE=8
```

## Instrucciones de la prueba

1. Crear un API Rest en Laravel que consuman la información de https://football-data.org. Debes crear un usuario (version free), se asociará un token a tu cuenta.

2. El API debe tener los siguientes endpoints:

- /competitions - Obtendra el detalle de todas las ligas 
- /competitions/<competition_id> - Obtiene el detalle de una liga especifica (Equipos y jugadores que luego serán almacenados localmente)
- /teams - Muestra todos los equipos que se han almacenado hasta el momento
- /teams/<team_id> - Muestra el detalle de los equipo seleccionado
- /players -  Muestra todos los jugadores almacenados hasta el momento

3. De la información de los jugadores se debe almacenar, nombre, posición número de camiseta

4. Debes asegurarte de enviar las respuestas adecuadas en todos los posibles casos, si se pierde conexión, si no existe el equipo o jugador.

5. Importante: Las consultas a la ApiRest de Football deben ser independientes a las consulta a tu propia ApiRest y considerar que este token free tiene una limitante de 10 request por minuto. Especificar en el README como abordaste tu solución.

6. Debes subirlo a un repositorio y enviarlo antes del domingo 1 de diciembre al mediodía.

## SOLUCIÓN

1. Se ha creado una clase `FootballData` que maneja las peticiones con el API Rest de football-data.org
2. Se ha creado una clase `Limiter` que es el encargado de contabilizar y verificar la cantidad de request permitidos en un minuto.
3. Se han creado las migraciones correspondientes para las tablas `competitions` `teams` `players` 
4. Se han creado los controladores con sus respectivos test.
5. Se han creado un "Job" llamado `UpdateCompetitionsTeams` que permite la obtención de datos en segundo plano.

Cuando el usuario ingresa a un recurso del tipo: /competitions/<competition_id> el sistema evalua si dicho torneo
se encuentra en nuestra base de datos local, en caso no se encuentre se envía un trabajo para obtener los equipos
del torneo y todos los jugadores. Este trabajo puede tomar varios minutos debido a la limitación de tiempo, sin
embargo este se realiza en segundo plano gracias al manejo de "Queues".

Totas las solicitudes se realizan directamente a la base de datos local, y en caso el elemento no exista se envía un error 404
u otros errores 500 definidos en el archivo `app/Exceptions/Handler.php`

Para poner en marcha completamente no olvide activar la ejecución de trabajos en segundo plano con el comando `queue:work`

```cmd
php artisan queue:work
``` 

Adolfo Cuadros
Software Developer
