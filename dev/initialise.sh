#!/bin/bash
# Initialise data

declare    data=local/data

declare -a datadirs=( users channels posts )
declare -a empty=( users/active channels/active )

#############################

rm -rf "$data" 2>/dev/null
mkdir "$data"

for dir in "${datadirs[@]}"
do
	mkdir "$data/$dir" 2>/dev/null
	chmod a+rwx "$data/$dir"
	
	printf '0' > "$data/$dir/lastid.int"
	chmod a+rw "$data/$dir/lastid.int"
done

for path in "${empty[@]}"
do
	echo '{}' > "$data/$path.json"
	chmod a+rw "$data/$path.json"
done

echo '{"name":null,"title":null,"creator":null,"type":null,"last-action":1418671148,"files":[],"users":{}}' > "$data/channels/-1.json"
chmod a+rw "$data/channels/-1.json"
