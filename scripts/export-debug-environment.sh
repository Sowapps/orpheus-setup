#!/usr/bin/env bash

# Call with dot => . ./scripts/debug.sh

# Change working directory to this file's folder
#cd "$(dirname "$0")" || return

#staticIp='192.168.0.1';

ip=${1:-$staticIp};

script_name=$( basename ${0#-} ) #- needed if sourced no path
this_script=$( basename ${BASH_SOURCE} )

if [[ "$script_name" == "$this_script" ]] ; then
    >&2 echo "Exporting variables is requiring you to source this script and do not run it (using . (dot) or source command)"
    # Running script, so we must exit it
    exit 1;
fi

export XDEBUG_MODE=debug
export XDEBUG_SESSION=1
export XDEBUG_CONFIG="${ip:+client_host=$ip }discover_client_host=no log_level=0"

echo "XDEBUG_CONFIG => $XDEBUG_CONFIG";
echo "XDEBUG Session enabled";

#xdebug.mode = debug
#xdebug.start_with_request = trigger
#xdebug.discover_client_host = true
#xdebug.client_port = 9003
#xdebug.idekey = PHPSTORM_DEV

