Remote Plugin NCPA
This project is licensed under Nagios Enterprises, LLC. All rights reserved.

Remote Plugin NCPA is a wizard that allows you to monitor a plugin on a remote device via NCPA.


______________________________
PREREQUISITES

Install NCPA on the machine the plugin will be run from
https://www.nagios.org/ncpa/

Add plugin to NCPA plugins folder

Windows default path:
C:\Program Files\Nagios\NCPA\plugins

Linux:
/usr/local/ncpa/plugins/

______________________________
INSTALLING THE WIZARD

From Nagios XI Web Interface:
Go to Admin > Manage Config Wizards
Click Browse, select remote_plugin_ncpa.zip
Click the upload icon.

______________________________
RUNNING THE WIZARD

From Nagios XI Web Interface:
Go to Configure > Configuration Wizards
Click on Remote Plugin NCPA

Follow steps outlined by wizard

NOTE* Plugin entry requires file extension to run properly
ex. "check_memory.py"