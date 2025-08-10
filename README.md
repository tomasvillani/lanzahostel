<p align="center"><a href="#"><img src="public/img/logo-extendido.png" width="400" alt="Lanzahostel"></a></p>

## Lanzahostel

**Lanzahostel** es una plataforma web innovadora creada para ofrecer una experiencia de gimnasio única y moderna. Este proyecto permite a los usuarios gestionar sus **reservas de clases** y **contratos de tarifas** de manera fácil y eficiente. Nuestra web facilita todo el proceso, permitiendo a los miembros elegir y reservar su clase ideal con solo unos clics.

Además, para mejorar la interacción y atención al cliente, **Lanzahostel** integra un **chatbot inteligente con IA**, disponible tanto para **invitados** como para **clientes registrados**. Este asistente virtual está diseñado para proporcionar respuestas sobre ejercicios, rutinas y dietas, ofreciendo asistencia personalizada en tiempo real. ¡Obtén recomendaciones y consejos de fitness al instante y mejora tu rendimiento en el gimnasio!

Con **Lanzahostel**, los usuarios disfrutan de:

- **Reservas fáciles y rápidas** para cualquier clase.
- Acceso a **detalles claros sobre tarifas y clases.**
- **Asistencia 24/7** gracias al chatbot basado en inteligencia artificial.

Este proyecto es ideal tanto para gimnasios que buscan optimizar su gestión como para los usuarios que desean disfrutar de un gimnasio de forma ágil, eficiente y con la tecnología más avanzada.

## ¿Qué pueden hacer los diferentes usuarios en Lanzahostel?

**Lanzahostel** está diseñado para ofrecer una experiencia de usuario completa, adaptada a las necesidades de **invitados, clientes** y **administradores**. Cada tipo de usuario tiene acceso a un conjunto específico de funcionalidades para que puedan aprovechar al máximo el servicio.

### Invitado:

Como **invitado**, puedes explorar y conocer lo que **Lanzahostel** tiene para ofrecer, sin necesidad de crear una cuenta. Disfruta de las siguientes funciones:

- **Consulta información básica** sobre el gimnasio: conoce nuestras **clases, eventos, horarios y profesores.**
- **Interacción con el chatbot:** recibe respuestas rápidas sobre **ejercicios, rutinas de entrenamiento y dietas.**

Invitar a tus amigos a conocer más sobre nosotros nunca fue tan fácil.

### Cliente:

Los **clientes** tienen acceso a todo lo que un invitado puede ver, además de una serie de funcionalidades exclusivas una vez se registran:

- **Consulta información detallada** sobre las clases, eventos y los profesores.
- **Uso del chatbot:** recibe asesoramiento personalizado en **ejercicios, dietas y rutinas** para mejorar tu rendimiento.
- **Reservas de clases:** después de **contratar una tarifa,** podrás reservar clases según tu disponibilidad, asegurando que tu experiencia sea siempre la mejor

Los clientes tienen el control para organizar sus entrenamientos y adaptarlos a sus necesidades.

### Administrador:

Como **administrador,** tendrás acceso completo a todas las funcionalidades para gestionar el gimnasio de manera eficiente

- **Gestión de clientes y reservas:** Puedes **crear, ver, editar y eliminar** reservas de clases de todos los usuarios, así como gestionar la información de los clientes.
- **Control total de las reservas:** Organiza y administra las reservas de clases para que el gimnasio funcione de manera óptima.
- **Gestión de eventos** e **información general:** Administra los eventos disponibles y ajusta los datos relacionados con las clases y horarios.

Aunque los administradores no pueden interactuar con el chatbot, tienen el control total para garantizar el buen funcionamiento del gimnasio.

## Instalación

Para ejecutar **Lanzahostel** localmente, sigue estos pasos:

### Requisitos previos:

- PHP >= 8.1 , y todas las extensiones necesarias:
```
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php php-cli php-mbstring php-xml php-bcmath php-curl php-zip unzip curl -y
```
Confirma la instalación de PHP:
```
php -v
```
- Composer
```
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```
Verifica la instalación:
```
composer --version
```
- MySQL
```
sudo apt install mysql-server php-mysql -y
```
Configura la base de datos y el usuario correspondiente:
```
sudo mysql
CREATE DATABASE gymtinajo;
CREATE USER 'gymtinajo'@'localhost' IDENTIFIED BY 'gymtinajo';
GRANT ALL PRIVILEGES ON *.* TO 'gymtinajo'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
- Node.js
```
sudo apt install nodejs npm
```
Confirma la instalación:
```
node -v
npm -v
```
- Git
```
sudo apt install git
```
Confirma la instalación:
```
git --version
```

1. Clona el repositorio:
```
git clone https://github.com/tomasvillani/Proyecto-final-DAW.git
```
2. Accede a la carpeta:
```
cd Proyecto-final-DAW
```
3. Otorga los permisos correspondientes:
```
sudo chmod 777 -R ./*
```
4. Instala las dependencias de Composer y de Node.js:
```
composer install
npm install
npm run build
```
5. Copia el archivo .env.example a un archivo .env.
6. Modifica estas líneas del .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gymtinajo
DB_USERNAME=gymtinajo
DB_PASSWORD=gymtinajo

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=4c2389d9e1f1e2
MAIL_PASSWORD=8b504d67215918
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=gymtinajo@gmail.com
MAIL_FROM_NAME="Lanzahostel"
```
7. Genera la clave de encriptación:
```
php artisan key:generate
```
8. Ejecuta las migraciones y estos seeders, para rellenar los datos en la base de datos:
```
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=TarifaSeeder
php artisan db:seed --class=HorarioSeeder
```
Si quieres, también puedes ejecutar este seeder:
```
php artisan db:seed --class=EventoSeeder
```
9. Para poder almacenar las imágenes para los eventos, ejecuta el siguiente comando:
```
php artisan storage:link
```
10. Inicia el servicio:
```
php artisan serve
```

De esta manera, si accedes por 127.0.0.1:8000, la página debe aparecer sin problema.

## Documentos de interés

Consulta los siguientes documentos para obtener información detallada sobre el proceso de desarrollo:

- [Documento de análisis](https://drive.google.com/file/d/129tjCpGYUdT33NKwFFzKBnu1K_T4uwNj/view?usp=sharing)
- [Documento de diseño](https://drive.google.com/file/d/1us5id3z-igcppqb8Gt1ynCrnyWBbJbNV/view?usp=sharing)

## Visita el Proyecto Online

Puedes visitar nuestra página web [aquí](https://gymtinajo.alwaysdata.net/)

## Vídeo de Youtube

Puedes ver el vídeo de nuestro proyecto en Youtube [aquí](https://youtu.be/3fXf20tTvhI?si=YNp7uXKW3M-TzClN)
