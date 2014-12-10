This directory contains all local-defined data.

Configuration:
* `configuration.json`: software configuration
* `quotations.json`: quotations shown to the user once connected

Data:
* `data/users`: data about users
    * `<id>.json`: data about a specific user
    * `active.json`: list of active users
    * `lastid.json`: last given id
* `data/channels`: data about channels
    * `<id>.json`: data about a specific channels
    * `active.json`: list of active channels
    * `lastid.json`: last given id
* `data/posts`: messages sent by users
    * `<id>.json`: partial list of messages for a channel
    * `alstid.json`: last given id
