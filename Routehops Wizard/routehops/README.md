# Routehops Wizard for Nagios XI

## Overview

The Routehops Wizard for Nagios XI allows you to monitor the number of hops to a target host using the `traceroute` command. This wizard helps you configure the necessary settings and thresholds for monitoring route hops.

## Features

- Monitor the number of hops to a target host.
- Set warning and critical thresholds for hop counts.
- Supports multiple protocols: UDP, TCP, and ICMP.
- Configurable verbosity for output messages.

## Prerequisites

- Nagios XI
- `traceroute` command installed on the Nagios XI server.

## Configuration

1. **Add the Wizard:**
   - Navigate to the Nagios XI web interface.
   - Go to `Admin` > `Manage Config Wizards`.
   - Click `Browse` and select the `routehops.zip` file.
   - Click `Upload`.

2. **Run the Wizard:**
   - Go to `Configure` > `Configuration Wizards`.
   - Select `Route Hops` from the list of available wizards.
   - Follow the steps to configure the host and service checks.

## Usage

- The wizard will guide you through the process of setting up the host and service checks.
- You can set warning and critical thresholds for the number of hops.
- You can choose the protocol to use for the `traceroute` command.
- The verbosity level can be adjusted to control the amount of output information.

## Command Line Arguments

The `check_route_hops.py` plugin supports the following command line arguments:

- `-H`, `--host`: Target host IP to check hop count (required).
- `-t`, `--timeout`: Timeout duration in seconds for the check (default is 3).
- `-w`, `--warning`: Warning threshold for hop count.
- `-c`, `--critical`: Critical threshold for hop count.
- `-v`, `--verbose`: The verbosity level of the output. Options: 0 (default), 1, 2.
- `-p`, `--protocol`: First protocol to attempt for traceroute (default: UDP).
- `--debug`: Enable debug mode.
- `--version`: Show the version of the plugin.

## Files

- `routehops.cfg`: Configuration template for the host and service checks.
- `check_route_hops.py`: Python script to perform the `traceroute` and check the number of hops.
- `setup_traceroute.sh`: Script to set the necessary capabilities for the `traceroute` command for the nagios user.
- `routehops.inc.php`: Main PHP file for the configuration wizard.
- `steps/step1.php`: Step 1 of the wizard.
- `steps/step2.php`: Step 2 of the wizard.
- `config.xml`: Configuration file for the wizard.

## License

This project is licensed under the Nagios Enterprises, LLC. All rights reserved.