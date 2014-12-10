#!/bin/bash
# Reset rights on the files.
# We need full access to 'others' because the server access the files this way
# in our restricted environment.

# Directories must be readable, web access is managed by .htaccess files
find \
	-type d \
	-exec chmod a+rx {} \;

# Web interface code files and configuration must be readable by anybody,
# but writable only by their owner.
find \
	-name '*.php' \
	-exec chmod go+r-w,u+rw {} \;

find \
	web/js web/css web/img web/font/ web/lib \
	-exec chmod go+r-w,u+rw {} \;

find \
	-name '*.css' \
	-exec chmod go+r-w,u+rw {} \;

# Data files must be readable and writable by anybody.
# We may not have enough rights to do this, depending on the configuration. Just ignore it.
chmod -R a+rw 'local/data' 2>/dev/null

# Scripts (and only scripts) must be executable by the owner (only).
for file in cli/*
do
	[ -f  "$file" ] &&
		grep -qF '#!/usr/bin/env php' "$file" &&
		chmod u+x,go-x "$file"
done
