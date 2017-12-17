#!/bin/bash
set -eux pipefail
cd /wordhat
vendor/bin/behat --format progress --config ./build/docker/behat.yml

