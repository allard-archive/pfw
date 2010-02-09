#!/bin/sh

#
# $1: is the connection. Either 'localhost' or 'ssh fw.example.com'
# $2: is the command (-r, -t, ...)
# $3: is the argument to the command
#

if [ `id -u` -gt 0 ]; then
	echo "You are not root"
	exit 1
fi

if [ $# -lt 1 ]; then
	echo "needs an argument"
	exit 1
fi

exec 2>/dev/null

if [ "$1" = "localhost" ]; then

  case $2 in

    (-r):	# Read config
	    TMPFILE=`mktemp /tmp/pf.conf.XXXXXXXXXX` || exit 1
	    chown www $TMPFILE
	    chmod 600 $TMPFILE
	    cat /etc/pf.conf > $TMPFILE
	    echo $TMPFILE
	    ;;

    (-t):	# Test Rulebase
      pfctl -n -f "$3" 2>&1
	    OUT=$?
	    rm -f "$3"
	    exit $OUT
	    ;;

    (-i):	# Install Rulebase
	    pfctl -n -f "$3" &&
	    install -o root -m 0600 "$3" /etc/pf.conf &&
	    pfctl -f /etc/pf.conf
	    OUT=$?
	    rm -f "$3"
	    exit $OUT
	    ;;

  esac

else

  case $2 in

    (-r):	# Read config
	    TMPFILE=`mktemp /tmp/pf.conf.XXXXXXXXXX` || exit 1
	    chown www $TMPFILE
	    chmod 600 $TMPFILE
	    $1 'cat /etc/pf.conf' > $TMPFILE
	    echo $TMPFILE
	    ;;

    (-t):	# Test Rulebase
	    TMPFILE=`$1 'mktemp /tmp/pf.conf.XXXXXXXXXX'` || exit 1
	    cat "$3" | $1 "cat > $TMPFILE"
	    $1 pfctl -n -f "$TMPFILE" 2>&1
	    OUT=$?
	    rm -f "$3"
	    $1 rm -f "$TMPFILE"
	    exit $OUT
	    ;;

    (-i):	# Install Rulebase
	    TMPFILE=`$1 'mktemp /tmp/pf.conf.XXXXXXXXXX'` || exit 1
	    cat "$3" | $1 "cat > $TMPFILE"
	    $1 install -o root -m 0600 "$TMPFILE" /etc/pf.conf &&
	    $1 pfctl -f /etc/pf.conf
	    OUT=$?
	    $1 rm -f $TMPFILE
	    rm -f "$3"
	    exit $OUT
	    ;;

  esac

fi
