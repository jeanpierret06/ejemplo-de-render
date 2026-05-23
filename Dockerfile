# Archivo Dockerfile - Funciona aquí, funcionará allá
# Define un contenedor PHP basado en Apache
FROM php:8.2-apache     

# Copia el proyecto completo
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Crea un archivo de configuración adicional
RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf

# Railway requiere exponer el puerto 80 para respuesta
EXPOSE 80

CMD ["apache2-foreground"]