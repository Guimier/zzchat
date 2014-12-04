# Agora
[![Build Status](https://travis-ci.org/Guimier/zzchat.svg?branch=master)](https://travis-ci.org/Guimier/zzchat)

Small chat system developped for *web development* course.

## Installation
    git clone https://github.com/Guimier/zzchat.git
    cd zzchat
    dev/initialise.sh

Please note that Agora uses PHP sessions. You should not install it on a server where other services use PHP sessions.

## Documentation

Directories documentation is maintained as `README.md` files.

Code documentation is maintained in Doxygen (PHP) and YUIdoc (JavaScript) in comments. You can run `ant doc-php` to build the PHP documentation and `ant doc-js` for the JavaScript one. You can run `ant doc` if you want to build both. They will be created as HTML files in the `doc` directory.

