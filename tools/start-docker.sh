#!/bin/bash

# Start this script from the publist4ubma2 directory,
# which will be mapped into the docker and allows
# easy developing with testing.
#
# See also the notes in the wiki:
# https://github.com/UB-Mannheim/publist4ubma2/wiki

set -x

TYPO3VERSION=7

# Stop already running containers
if docker ps | grep -q typo3-web ; then
    docker stop typo3-web
fi
if docker ps | grep -q typo3-db ; then
    docker stop typo3-db
fi

if [[ "$1" = "--new" ]]; then
    if docker ps -a | grep -q typo3-db ; then
        docker rm typo3-db
    fi
    if docker ps -a | grep -q typo3-web ; then
        docker rm typo3-web
    fi
    echo "Renew docker containers (start from scratch again)"
    # Run container with database
    docker run -d --name typo3-db \
        -e MYSQL_ROOT_PASSWORD=yoursupersecretpassword \
        -e MYSQL_USER=typo3 \
        -e MYSQL_PASSWORD=yourothersupersecretpassword \
        -e MYSQL_DATABASE=typo3 \
      mariadb:latest \
        --character-set-server=utf8 \
        --collation-server=utf8_unicode_ci
    # Run container with typo3, sync with this directory
    docker run -d --name typo3-web \
        --link typo3-db:db \
        -p 80:80 \
        -v `pwd`:/var/www/html/typo3conf/ext/publist4ubma2 \
      martinhelmich/typo3:"$TYPO3VERSION"
else
    if ! docker ps -a | grep -q typo3-db ; then
       echo "Database container typo3-db not found, use --new to start/renew the containers"
       exit 1
    fi
    if ! docker ps -a | grep -q typo3-web ; then
       echo "TYPO3 container typo3-web not found, use --new to start/renew the containers"
       exit 2
    fi
    echo "Continue the docker containers (use --new to renew the containers from scratch)"
    docker start typo3-db
    docker start typo3-web
fi

set +x
