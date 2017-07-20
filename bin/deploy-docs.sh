#!/bin/bash
cd ..
pip install --user --upgrade pip
pip install --user --upgrade pymdown-extensions pygments mkdocs mkdocs-material
pip show mkdocs-material | grep -E ^Version
mkdocs gh-deploy
