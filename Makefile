VERSION = $(shell git describe --tags --always --dirty)
BRANCH = $(shell git rev-parse --abbrev-ref HEAD)
CONTAINER = vm-php

.PHONY: help shell test cover

all: help

help:
	@echo
	@echo "VERSION: $(VERSION)"
	@echo "BRANCH: $(BRANCH)"
	@echo
	@echo "usage: make <command>"
	@echo
	@echo "commands:"
	@echo "    help             - show this help"
	@echo "    dev              - compose up dev environment and apply JSON fixtures"
	@echo "    nodev            - compose down dev environment"
	@echo "    shell            - enter the PHP container"
	@echo "    cache            - execute cache:clear"
	@echo "    tree             - show git log tree"
	@echo "    purge            - removes ALL docker containers, images and volumes in dev machine"
	@echo

dev:
	@docker compose up -d

nodev:
	@docker compose down

shell:
	@docker exec -ti $(CONTAINER) bash

cache:
	@docker exec $(CONTAINER) php bin/console cache:clear

tree:
	git log --graph --oneline --decorate

purge:
	@sh clean-all.sh
