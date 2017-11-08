# hiku-php

This is a php implementation of the hiku API.

You can read about it in more detail on https://piandmore.wordpress.com/tag/hiku

01 Simple start
contains a very basic webhook which just takes the data, saves it to a file and send an OK back

02 Receiving events - Development
contains a more extensive version of the webhook which has basic error handling and splitting of the different events. It also accomodates for development features such as replaying an event and creating a fake event

03 Receiving events
contains the same version as 02 except that all development code has been removed

