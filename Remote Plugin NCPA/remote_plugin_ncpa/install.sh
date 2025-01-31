#!/bin/bash

if [ `command -v yum` ]; then
	yum install lftp -y
else
	apt-get install -y lftp
fi
exit 0
