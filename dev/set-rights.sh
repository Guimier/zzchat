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
	-name '*.js' \
	-exec chmod go+r-w,u+rw {} \;

# Data files must be readable and writable by anybody.
chmod -R a+rw 'local/data'

# Scripts must be executable by the owner (only).
chmod u+x,go-x cli/*.php
