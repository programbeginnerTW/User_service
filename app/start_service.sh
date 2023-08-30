#!/bin/bash

if [ ! -d "./vendor" ]; then
    composer install
fi

if [ ! -f "./rr" ]; then
    php spark ciroad:init
    chmod 776 ./rr
fi

./rr serve