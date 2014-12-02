#!/bin/bash
# Initialise data

declare    data=local/data

declare -a datadirs=( users channels posts )
declare -a counters=( lastchannel lastuser lastpostfile )
declare -a empty=( users/activeusers channels/activechannels )

#############################

rm -r "$data" 2>/dev/null
mkdir "$data"

for dir in "${datadirs[@]}"
do
	mkdir "$data/$dir" 2>/dev/null
	chmod a+rwx "$data/$dir"
done

for counter in "${counters[@]}"
do
	printf '0' > "$data/$counter.int"
	chmod a+rw "$data/$counter.int"
done

for path in "${empty[@]}"
do
	echo '{}' > "$data/$path.json"
	chmod a+rw "$data/$path.json"
done

