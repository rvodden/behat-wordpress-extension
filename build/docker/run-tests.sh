#!/bin/bash
set -eux pipefail
cd /wordhat
vendor/bin/behat --no-colors --config ./build/docker/behat.yml -o std -f progress -o behat-report.html -f pretty

