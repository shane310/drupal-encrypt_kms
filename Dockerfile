FROM debian:stretch

WORKDIR /data

# Install git, curl and yamllint.
RUN apt-get update && \
    apt-get install --yes --force-yes git php php-mbstring php-xml curl python-pip && \
    pip install yamllint && \
    rm -rf /var/lib/apt/lists/* ~/.cache

# Latest version of jq.
RUN curl -s -L https://github.com/stedolan/jq/releases/download/jq-1.5/jq-linux64 -o /usr/local/bin/jq && \
    chmod +rx /usr/local/bin/jq

# PHP, composer and tooling.
RUN curl -s -L https://getcomposer.org/composer.phar -o /usr/local/bin/composer && \
    chmod +rx /usr/local/bin/composer

RUN composer global config minimum-stability dev && \
    composer global require \
    "drupal/coder:^8.2.12" \
    "squizlabs/php_codesniffer:^2.9" \
    "dealerdirect/phpcodesniffer-composer-installer"

ENV PATH="${PATH}:/root/.composer/vendor/bin"

RUN mkdir -p ~/.ssh && \
    echo "Host git.drupal.org" >> ~/.ssh/config && \
    echo "  StrictHostKeyChecking no" >> ~/.ssh/config
