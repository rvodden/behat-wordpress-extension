#!/bin/bash
pip install --user --upgrade pip
pip install --user --upgrade pymdown-extensions pygments mkdocs mkdocs-material
mkdocs gh-deploy
