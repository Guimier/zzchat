# Agora
[![Build Status](https://travis-ci.org/Guimier/zzchat.svg?branch=master)](https://travis-ci.org/Guimier/zzchat)

Small chat system developped for *web development* course.

## Installation
    git clone https://github.com/Guimier/zzchat.git
    cd zzchat
    dev/initialise.sh

Please note that Agora uses PHP sessions. You should not install it on a server where other services close PHP sessions (services using a single session—like Agora—will work).

If you want to use several instances on a single server, be sure to set a different prefix for each one (`cli/configuration set --key=prefix --value=<your prefix>`).

## Documentation

Directories documentation is maintained as `README.md` files.

Code documentation is maintained in Doxygen (PHP) and YUIdoc (JavaScript) syntaxes in comments. You can run `ant doc-php` to build the PHP documentation and `ant doc-js` for the JavaScript one. You can run `ant doc` if you want to build both. They will be created as HTML files in the `doc` directory.

Administrator commands (in `cli` direcrory) contain their documentation. Run `<command> --help` to see it.

Available configuration is documented by the configuration tool. Run `cli/configuration show --describe` to see it.

