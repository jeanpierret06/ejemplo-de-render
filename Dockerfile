# Define un contenedor PHP basado en Apache
FROM php:8.2-apache     

# Copia el proyecto completo al directorio raíz de Apache
COPY . /var/www/html/

# Asegura los permisos correctos para que Apache pueda leer los archivos
RUN chown -R www-data:www-data /var/www/html

# CONFIGURACIÓN DE PUERTO DINÁMICO PARA RENDER
# Configuramos Apache para que use la variable $PORT que Render le inyecta obligatoriamente
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# Informamos a la infraestructura el puerto dinámico (Opcional pero recomendado en Render)
EXPOSE ${PORT}

CMD ["apache2-foreground"]
