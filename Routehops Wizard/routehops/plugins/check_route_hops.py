#!/usr/bin/env python3
##
## Check Route Hops
## Copyright (c) 2025 Nagios Enterprises, LLC. All rights reserved.
##

import sys
import traceback
import argparse
import subprocess

OK = 0
WARNING = 1
CRITICAL = 2

_args = None

def main():
    global _args
    try:
        # Set up the ArgumentParser
        parser = argparse.ArgumentParser(
            description='This script checks the number of hops to a target host using traceroute. '
                        'You can define warning and critical thresholds for hop counts. '
                        'If the host is unreachable with the default protocol (UDP) other protocols will be tried.')
        
        # Define the arguments
        parser.add_argument('-H', '--host', required=True, type=str, help='Target host IP to check hop count')
        parser.add_argument('-t', '--timeout', default=3, type=int, help='Timeout duration in seconds for the check (default is 3)')
        parser.add_argument('-w', '--warning', required=False, type=int, help='Warning threshold for hop count')
        parser.add_argument('-c', '--critical', required=False, type=int, help='Critical threshold for hop count')
        parser.add_argument('-v', '--verbose', required=False, type=int, default=0, help=(
            'The verbosity level of the output. '
            '(0: Single line summary, '
            '1: Single line with additional information, '
            '2: Multi line with configuration debug output)'
        ))
        parser.add_argument('-p', '--protocol', type=str, default='UDP', help='First protocol to attempt for traceroute (default: UDP)')
        parser.add_argument('--debug', action='store_true', help='Enable debug mode')
        parser.add_argument('--version', action='version', version='%(prog)s 1.1')

        _args = parser.parse_args(sys.argv[1:])
            
        if _args.debug:
            print("Debug Mode Enabled.")
            print(f"Parsed arguments: {_args}")
    
    except argparse.ArgumentError as e:
        print(f"CRITICAL - Argument error: {str(e)}")
        if _args.debug:
            traceback.print_exc()
        sys.exit(CRITICAL)
    except Exception as e:
        print(f"CRITICAL - An error occurred during argument parsing: {str(e)}")
        if _args.debug:
            print(str(e))
            traceback.print_exc()
            print(f"Command: {' '.join(sys.argv)}")
        sys.exit(CRITICAL)

    # Check that a host was provided in args
    if not _args.host:
        print("CRITICAL - A host argument was not provided (use -H)")
        sys.exit(CRITICAL)

    # Find the number of hops to the host using different protocols
    protocols = ['UDP', 'ICMP', 'TCP']
    if _args.protocol in protocols:
        protocols.remove(_args.protocol)
        protocols.insert(0, _args.protocol)

    hops = 0
    used_protocol = _args.protocol

    # Check the default protocol first
    hops = get_route_hops(_args.protocol)

    # If the default protocol fails, try the other protocols
    if hops == 0:
        for protocol in protocols[1:]:
            hops = get_route_hops(protocol)
            if hops > 0:
                used_protocol = protocol
                break

    if hops == 0:
        print("CRITICAL - Failed to determine hop count for all protocols")
        sys.exit(CRITICAL)

    # Set the status code based on the number of hops to the host
    status_code = OK
    if _args.warning and hops >= _args.warning:
        status_code = WARNING
    if _args.critical and hops >= _args.critical:
        status_code = CRITICAL
    
    status_dict = {
        0: "OK",
        1: "WARNING",
        2: "CRITICAL",
    }

    # Build the status message
    message = ''
    if (hops == 1):
        message = f"{status_dict[status_code]} - {hops} hop was counted to {_args.host}"
    else:
        message = f"{status_dict[status_code]} - {hops} hops were counted to {_args.host}"
    
    if _args.verbose >= 1:
        message += f" using {used_protocol}"
        if _args.verbose >= 2:
            message += f"\n{' '.join(sys.argv)}"

    # Add performance data
    perf_data = f"'hops'={hops};{_args.warning if _args.warning else ''};{_args.critical if _args.critical else ''};;"
    message += f" | {perf_data}"

    print(message)
    sys.exit(status_code)


def get_route_hops(protocol):
    global _args
    if _args.debug:
        print(f"Checking hops using {protocol} for host: {_args.host}...")

    try:
        # Run the traceroute command for the host IP
        result = None
        if protocol == "TCP":
            result = subprocess.run(['traceroute', _args.host, '-T'], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, timeout=_args.timeout)
        elif protocol == "UDP":
            result = subprocess.run(['traceroute', _args.host], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, timeout=_args.timeout)
        elif protocol == "ICMP":
            result = subprocess.run(['traceroute', _args.host, '-I'], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, timeout=_args.timeout)
        
        if result.returncode != 0:
            if _args.debug:
                print(f"ERROR: Could not run subprocess command - {result.stderr}")
            return 0
        
        # Count the number of hops (lines returned by traceroute)
        hops = len(result.stdout.splitlines()) - 1
        
        if _args.debug:
            print(f"Traceroute {protocol} results: {result.stdout}")
            print(f"Number of hops: {hops}")

        return hops if hops > 0 else 0
    
    except subprocess.TimeoutExpired:
        if _args.debug:
            print(f"EXCEPTION: Traceroute for {protocol} timed out after {_args.timeout} seconds.")
        return 0
    except Exception as e:
        if _args.debug:
            print(str(e))
            traceback.print_exc()
        print(f"EXCEPTION: An error occurred while running traceroute for {protocol}.")
        return 0


if __name__ == "__main__":
    main()
