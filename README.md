# sesion2_cripto

Requerimientos:

Ubuntu18.04, servidor apache, php7.2, libreria libsodium

Hay que cambiar la configuración del archivo de php llamado php.ini, que se encuentra en el directorio /etc/php/7.2/apache2/ y cambiar el parámetro upload_max_filesize ( asignarle el tamaño del archivo que se desea encriptar) 

Acceso a la aplicación:

http://localhost/ejecutar.html

Salida del programa:

Los archivos encriptados se guardan en el directorio /var/www/html


Referencias:

https://libsodium.gitbook.io/doc/
https://github.com/jedisct1/libsodium-php
