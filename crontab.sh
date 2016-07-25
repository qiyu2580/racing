#!/bin/bash

step=2 #间隔的秒数，不能大于60

for (( i = 0; i < 60; i=(i+step) )); do
    $(php /home/vagrant/Code/laravel/artisan Racing:Run)
    sleep $step
done

exit 0
