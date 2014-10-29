# Coding style guide

## General
* The braces must be on their own line.
* The opening brace and the closing brace must be at the same level of indentation.
* Parenthesis must have space inside: `myFunction( content )`. Unless they are empty: `myFunction()`.
* Parenthesis must be preceded by a space after languages keywords: `if ( content )`
* Parenthesis must immediately follow in others situations: `yFunction( content )`
* Commas are not preceded by spaces and followed by one space (or carriage return in case of long line).
* Semi-colons are preceded and followed by a space (or followed by a carriage return at end of line).

Bloc comments are opened on one line by `/*` and closed by another line ` */`. Lines inside start with ` * ` followed by any text needed.

Line comments use `/* Text */` form and take place before the instructions they comment. Short comments may use `// Text` form (after the line they comment).

## Scripts
* Variables and functions names are in *lowerCamelCase*: `myGreatVariable`.
* Classes names (special functions in JavaScript) are in *UpperCamelCase*: `MyGreatClass`.

## PHP
* Operators like `::`, `->` are neither preceded nor followed by spaces.

## JavaScript
* Variables of DOM interface are prefixed by `d`: `dLink`.
* Variables of type jQuery are prefixed by `$`: `$link`.

## CSS
* One line = one property.
* One line = one selector.
