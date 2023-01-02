FROM el7cosmos/nginx-unit-drupal:1.27.0-php8.1

COPY docker/variables-order.ini ${PHP_INI_DIR}/conf.d/

#COPY docker/config.json /docker-entrypoint.d/
COPY docker/files.sh /docker-entrypoint.d/
#COPY docker/options.sh /docker-entrypoint.d/

COPY . /opt/drupal/

#RUN \
#  mkdir /opt/drupal/web/sites/default/files \
#  chown -R unit:unit /opt/drupal/web/sites/default/files
