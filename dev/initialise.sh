#!/bin/bash
# Initialise data

data=local/data

datadirs=( users channels posts )
counters=( lastchannel lastuser lastpostfile )

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

echo '[]' > "$data/activechannels.json"
chmod a+rw "$data/activechannels.json"

