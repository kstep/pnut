.SUFFIXES: .php .phtml

empty := 
comma := ,
space := $(empty) $(empty)

PROGRAMDIRS := classes models controllers
VARDIRS := cache attachments
ROOTPHPFILES := index.php loader.php compat.php debug.php install.php
PARSABLEDIRS := $(PROGRAMDIRS) templates
DEPLOYOBJS := $(PARSABLEDIRS) $(ROOTPHPFILES) static locale migrations Makefile

EACHPROG := for i in $(PARSABLEDIRS); do find ./$$i -name ".svn" -prune -o \( -name "*.php" -o -name "*.phtml" \) 
DONE := ; done

DBPARAMS := `awk 'BEGIN { FS="[<>]"; ORS=" " } /<hostname>(.*)<\/hostname>/ { print "-h" $$3 } /<port>([0-9]\+)<\/port>/ { print "-P" $$3 } /<username>(.*)<\/username>/ { print "-u" $$3 } /<password>(.*)<\/password>/ { print "-p" $$3 } /<database>(.*)<\/database>/ { print $$3 }' < ./configs/db.xml`

# Cleanup tasks
cleancache:
	rm -rf cache/_*

cleandoc:
	rm -rf doc/*

cleanbak:
	find . -name "*.bak" \( -execdir perl -e '$$_ = "{}";s/\.bak//;system("diff -q {} $$_");exit ($$? ? 1: 0);' \; -delete \)

cleanallbak:
	find . -name "*.bak" -delete

clean: cleandoc cleancache cleanbak
	cd configs/ && $(MAKE) clean

# Installation tasks

.lvimrc:
	sed -e 's#@SITEPATH@#$(PWD)#g' < .lvimrc.in > .lvimrc

config: .lvimrc
	cd configs/ && $(MAKE)

$(VARDIRS):
	mkdir -p $@

setperm: $(VARDIRS)
	chmod a+rwx $^

install: config setperm

# Development tasks
syntax:
	$(EACHPROG) -exec php -l {} \; 2>&1 | grep -v "No syntax errors detected in " $(DONE) || exit 0

doc: $(PROGRAMDIRS)
	mkdir -p doc
	phpdoc -q -i *test*.php -d $(subst $(space),$(comma),$(PROGRAMDIRS)) -t doc -o HTML:default:phpedit
	touch doc

tags: .lvimrc $(PROGRAMDIRS)
	$(EACHPROG) -print $(DONE) | ctags -f ./tags -L - -h ".php" -R --exclude="\.svn" --totals=yes --tag-relative=yes --PHP-kinds=+cf

update:
	$(EACHPROG) -exec cp -vu {} $(TARGETDIR){} \; $(DONE)
	find ./static -name ".svn" -prune -o ! \( -name "*.zip" -o -name "*.bmp" -o -type d \) -exec cp -vu {} $(TARGETDIR){} \;
	cp -vu ./*.php $(TARGETDIR)

dev: syntax tags doc

# Code base support tasks
notsvn: .svn
	@find . \( -name ".svn" -o -name ".git" -o -name "cache" -o -name "attachments" \) -prune -o \( -type f -print \) | perl -ne 'chomp; ($$dir, $$name) = (m{(.+)/(.+)}); if (! -f $$dir."/.svn/text-base/".$$name.".svn-base") { print $$_,"\n" };'

lsdbgcode:
	$(EACHPROG) -exec egrep "echo|print_r|vardump|Profile::|fb\(" {} + $(DONE); exit 0

rmdbgcode:
	$(EACHPROG) -exec sed -i.bak -e "/print_r\|vardump\|Profile::/d" {} \; $(DONE)

# Backup tasks
backupdb: ./configs/db.xml
	mkdir -p ./backups
	mysqldump $(DBPARAMS) --result-file=./backups/dump_`date +%Y%m%d_%H%M`.sql

structdb: ./configs/db.xml
	mkdir -p ./backups
	mysqldump $(DBPARAMS) --add-drop-table --no-data --result-file=./backups/struct_`date +%Y%m%d_%H%M`.sql

fulldbdump: backupdb structdb

# Deployment tasks
install.tgz: $(DEPLOYOBJS)
	tar czf $@ --exclude=".svn" $^

update.tgz: $(DEPLOYOBJS)
	if test -f install.tgz; then tar czf $@ --exclude=".svn" `for i in $^; do find $$i -newer install.tgz -type f; done`; fi

migrate: migrations/*.sql
	cat $? | mysql $(DBPARAMS)

migrateone:
	test -f migrations/$(MIGRATION)_*.sql && cat migrations/$(MIGRATION)_*.sql | mysql $(DBPARAMS)

sshinstall: install.tgz
	scp $< $(SSHHOST):$(SSHDIR) && ssh $(SSHHOST) 'cd $(SSHDIR) && tar xzf $<'

sshupdate: update.tgz
	scp $< $(SSHHOST):$(SSHDIR) && ssh $(SSHHOST) 'cd $(SSHDIR) && tar xzf $<'

all: install

.PHONY: cleancache cleandoc cleanbak cleanallbak clean \
		config setperm install \
		syntax update dev \
		notsvn lsdbgcode rmdbgcode \
		backupdb structdb fulldbdump migrate \
		all

