FROM php:8.5-cli

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        ca-certificates \
    && groupadd --gid ${GROUP_ID} appgroup \
    && useradd \
        --uid ${USER_ID} \
        --gid appgroup \
        --create-home \
        --shell /bin/bash \
        appuser \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

USER appuser