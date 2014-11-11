#!/bin/bash

##### Configuration #####

# Root directory of the repository
declare root="$( dirname "$( dirname "$0" )"  )"

# Relative paths to directories to explore
declare -a dirs=( 'back' )

# Relative path to the outpu file
declare list='back/classes.json'

##### Functions #####

# Extract the name of a class from its header.
# Usage: className $header…
# @param $header Unescaped header line.
className()
{
	while [ "$1" != 'class' ]
	do
		shift
	done
	echo $2
}

# Extract the classes from a set of files.
# Usage: extractClasses $file1 [$file2 […]] > $partialJson
# @param $file# File to search in.
# @return Partial JSON object.
extractClasses()
{
	while [ $# -gt 0 ]
	do
		egrep '(^| )class ' "$1" | # Line must contain “class”
			grep -v \* | # But not be inside a comment
			while read ligne
		do
			class=$( className $ligne )
			printf '\t"%s": "%s"\n' "$class" "$1"
		done
		shift
	done
}

# Build JSON from partial JSON
# Usage: toJson < $partialJson > $json
toJson()
{
	echo '{'
	read lastLine
	
	while read line
	do
		printf '\t%s,\n' "$lastLine"
		lastLine=$line
	done

	printf '\t%s\n' "$lastLine"

	echo '}'
}

##### Execution #####

# Go to $root 
cd "$root"

# List file
declare -a files=( $( find "${dirs[@]}" -name '*.php' ) )

extractClasses "${files[@]}" |
	sort --ignore-case |
	toJson > "$list"

