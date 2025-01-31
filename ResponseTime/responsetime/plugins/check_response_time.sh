#!/bin/bash

#Nagios exit codes
OK=0
WARNING=1
CRITICAL=2
UNKNOWN=3

#initial values
HOST=""
WAR_THRESHOLD=""
CRT_THRESHOLD=""

#parse arguments
while getopts ":H:w:c:" arg; do
	case $arg in
		H)HOST=${OPTARG} ;;
		w)WAR_THRESHOLD=${OPTARG} ;;
		c)CRT_THRESHOLD=${OPTARG} ;;
	esac
done

ECHO=$(which echo)

#validate arguments
if [ -z "$HOST" ] || [ -z "$WAR_THRESHOLD" ] || [ -z "$CRT_THRESHOLD" ]; then
	echo "UNKNOWN - Invalid Arguments. Usage : $0 -H <host> -w <warning_threshold_in_ms> -c <critical_threshold_in_ms>"
	echo "Host: $HOST, W: $WAR_THRESHOLD, C: $CRT_THRESHOLD "
	exit $UNKNOWN
fi

#stripping protocol (http or https)
HOST=$(echo "$HOST" | sed 's#^https\?://##')

#check if the host is a website or not
if [[ "$HOST" == *".com"* || "$HOST" == *".org"* || "$HOST" == *".net"* || "$HOST" == *".edu"* ]]; then
	START_TIME=$(date +%s%3N)
	curl -o /dev/null -s -w "%{http_code}" "$HOST" > /dev/null
	if [[ $? -ne 0 ]]; then 
		echo "CRITICAL - Unable to connect to $HOST | 'responsetime'=${RESPONSE_TIME}ms;${WAR_THRESHOLD};${CRT_THRESHOLD};0;"
		exit CRITICAL
	fi 
	END_TIME=$(date +%s%3N)
else
	START_TIME=$(date +%s%3N)
	ping -c 1 -W 1 "$HOST" > /dev/null
	if [[ $? -ne 0 ]]; then
		echo "CRITICAL - Unable to ping to $HOST | 'responsetime'=${RESPONSE_TIME}ms;${WAR_THRESHOLD};${CRT_THRESHOLD};0;"
		exit CRITICAL
	fi
	END_TIME=$(date +%s%3N)
fi

RESPONSE_TIME=$((END_TIME - START_TIME))

#compare response time  to threshold
if [ "$RESPONSE_TIME" -ge "$CRT_THRESHOLD" ]; then 
	echo "CRITICAL - Response time is ${RESPONSE_TIME} ms,exceeding the critical threshold of ${CRT_THRESHOLD} ms for $HOST | 'responsetime'=${RESPONSE_TIME}ms;${WAR_THRESHOLD};${CRT_THRESHOLD};0;"
	exit $CRITICAL
elif [ "$RESPONSE_TIME" -ge "$WAR_THRESHOLD" ]; then
	echo "WARNING - Response time is ${RESPONSE_TIME} ms,exceeding the warning threshold of ${WAR_THRESHOLD} ms for $HOST | 'responsetime'=${RESPONSE_TIME}ms;${WAR_THRESHOLD};${CRT_THRESHOLD};0;"
	exit $WARNING
else
	echo "OK - Response time is ${RESPONSE_TIME} ms for $HOST | 'responsetime'=${RESPONSE_TIME}ms;${WAR_THRESHOLD};${CRT_THRESHOLD};0;"
	exit $OK
fi
