.SUFFIXES: .po .mo

ru/LC_MESSAGES/%.mo: ru/LC_MESSAGES/%.po
	msgfmt $< -o $@

ru/LC_MESSAGES/pnut.pot: ../models
	xgettext -o $@ $</*.php

all: ru/LC_MESSAGES/pnut.mo

.PHONY: all
