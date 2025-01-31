#!/bin/bash

# This script sets the necessary capabilities for the traceroute command.

# Ensure the script is run with root privileges
if [ "$(id -u)" -ne 0 ]; then
    echo "This script must be run as root. Please use sudo."
    exit 1
fi

# Find the path of the traceroute command
TRACEROUTE_PATH=$(which traceroute)

if [ -z "$TRACEROUTE_PATH" ]; then
    echo "Failed to find the traceroute command. Please ensure it is installed."
    exit 1
fi

# Add sudoers configuration for nagios user
echo "Configuring sudoers for nagios user..."
echo "nagios ALL=(ALL) NOPASSWD: /usr/sbin/setcap" | sudo tee /etc/sudoers.d/nagios_setcap

# Set the capabilities
sudo setcap cap_net_raw+ep "$TRACEROUTE_PATH"

# Check if the command was successful
if [ $? -eq 0 ]; then
    echo "Capabilities set successfully for $TRACEROUTE_PATH"
else
    echo "Failed to set capabilities for $TRACEROUTE_PATH" >&2
    exit 1
fi

# Clean up sudoers configuration file
echo "Cleaning up sudoers configuration..."
sudo rm /etc/sudoers.d/nagios_setcap

echo "Setup completed successfully."