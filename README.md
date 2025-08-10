<p align="center"><a href="#"><img src="public/img/logo-extendido.png" width="400" alt="Lanzahostel"></a></p>

## Lanzahostel

**Lanzahostel** es una plataforma web diseñada para conectar a empresas del sector hostelero en Lanzarote con personas que buscan empleo en esta área. La plataforma facilita la publicación de ofertas laborales, la recepción de solicitudes y la gestión de procesos de selección de forma sencilla y eficiente.

Los usuarios pueden registrarse como empresas para publicar sus vacantes y administrar las solicitudes recibidas, o como clientes para buscar empleos, postularse y gestionar sus aplicaciones. Lanzahostel busca simplificar la intermediación laboral en el sector hostelero, optimizando el proceso tanto para empleadores como para candidatos.

Con una interfaz intuitiva y funcionalidades adaptadas a las necesidades locales, esta plataforma pretende potenciar las oportunidades laborales en Lanzarote, fomentando un entorno digital que facilite el encuentro entre oferta y demanda de empleo en hostelería.

## ¿Qué pueden hacer los diferentes usuarios en Lanzahostel?

En **Lanzahostel**, cada tipo de usuario tiene acceso a diferentes funcionalidades que se adaptan a sus necesidades.

### Invitado:

Los usuarios no registrados pueden navegar y consultar las ofertas de empleo disponibles en la plataforma. Esto les permite explorar las oportunidades laborales en el sector hostelero de Lanzarote sin necesidad de crear una cuenta.

### Cliente:

Los usuarios registrados como clientes pueden buscar y postularse a las ofertas de trabajo publicadas. Además, tienen la posibilidad de gestionar sus solicitudes, ver el estado de sus postulaciones, y eliminar aquellas que ya no les interesen. También pueden actualizar su perfil y cargar su currículum para facilitar el proceso de aplicación.

### Empresa:

Los usuarios registrados como empresas pueden crear, editar y eliminar ofertas de empleo para promocionar sus vacantes. Asimismo, pueden revisar las solicitudes recibidas, aceptarlas o rechazarlas, y gestionar la información de sus puestos publicados. Esta funcionalidad facilita el proceso de selección y contratación dentro del sector hostelero.

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
CREATE DATABASE lanzahostel;
CREATE USER 'lanzahostel'@'localhost' IDENTIFIED BY 'lanzahostel';
GRANT ALL PRIVILEGES ON *.* TO 'lanzahostel'@'localhost';
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
git clone https://github.com/tomasvillani/lanzahostel.git
```
2. Accede a la carpeta:
```
cd lanzahostel
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
DB_DATABASE=lanzahostel
DB_USERNAME=lanzahostel
DB_PASSWORD=lanzahostel
```
7. Genera la clave de encriptación:
```
php artisan key:generate
```
8. Para poder almacenar los archivos para los puestos, la foto de perfil y los CV, ejecuta el siguiente comando:
```
php artisan storage:link
```
9. Inicia el servicio:
```
php artisan serve
```

De esta manera, si accedes por 127.0.0.1:8000, la página debe aparecer sin problema.

## Documentos de interés

Consulta los siguientes documentos para obtener información detallada sobre el proceso de desarrollo:

- [Documento de análisis](https://drive.google.com/file/d/1NgfZBicKFmZvh08xHls8KokR7fZ8tLiU/view?usp=sharing)
- [Documento de diseño](https://drive.google.com/file/d/1vrPlhSLXCibZ0s_N31kjwoodMbJMWja8/view?usp=sharing)

## Vídeo de Youtube

Puedes ver el vídeo del proyecto en Youtube [aquí](https://youtu.be/dymaiI7xIOE?si=bEGuYPHPl_w8gKMw)
