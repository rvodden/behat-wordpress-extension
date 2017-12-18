#!/bin/bash
set -eux pipefail
cd /wordhat
vendor/bin/phing wordhat:prepare-docker-config
vendor/bin/phing behat:exec-tests

