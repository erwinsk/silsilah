#!/bin/bash
# Start the first process
./home/application/run_ssh.sh &

# Start the second process
./home/application/run_apache.sh &

# Wait for any process to exit
wait -n

# Exit with status of process that exited first
exit $?
