FROM php:8.5-cli

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        ca-certificates \
        gosu \
    && if ! getent group "${GROUP_ID}" >/dev/null; then \
        groupadd --gid "${GROUP_ID}" appgroup; \
    fi \
    && useradd \
        --uid "${USER_ID}" \
        --gid "${GROUP_ID}" \
        --create-home \
        --shell /bin/bash \
        appuser \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENV COMPOSER_CACHE_DIR=/var/cache/composer
ENV XDG_CACHE_HOME=/var/cache

WORKDIR /app

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["bash"]