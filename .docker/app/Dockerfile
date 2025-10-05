FROM ubuntu:22.04

ENV NVM_VERSION v0.40.1
ENV NODE_VERSION v23.5.0
ENV NVM_DIR /usr/local/nvm

ENV TZ=America/Sao_Paulo

WORKDIR /var/www/html

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get upgrade -y \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin fswatch nano mariadb-client nginx  \
    && apt-get update \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Configurando PHP
RUN curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.3 \ 
    php8.3-fpm \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-bcmath \
    php8.3-curl \
    php8.3-opcache \
    php8.3-redis \
    php8.3-mysql \
    php8.3-zip \
    php8.3-gd \
    php8.3-imagick \
    php8.3-intl \
    php8.3-readline \
    php8.3-xmlrpc \
    php8.3-xsl \
    php8.3-xmlrpc \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Configurando NVM
RUN mkdir $NVM_DIR && curl -o- https://raw.githubusercontent.com/creationix/nvm/$NVM_VERSION/install.sh | bash

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

RUN echo "source $NVM_DIR/nvm.sh && \
    nvm install $NODE_VERSION && \
    nvm alias default $NODE_VERSION && \
    nvm use default" | bash

COPY fpm-pool.conf /etc/php/8.3/fpm/pool.d/www.conf
COPY supervisord.conf /etc/supervisord.conf
COPY nginx.conf /etc/nginx/nginx.conf

EXPOSE 80/tcp

CMD ["supervisord", "-c", "/etc/supervisord.conf"]