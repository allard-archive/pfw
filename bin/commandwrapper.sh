#!/bin/sh

if [ $# -lt 1 ]; then
	echo "needs an argument"
	exit 1
fi

exec 2>/dev/null

if [ "$1" = "localhost" ]; then

  case $2 in

  	(-df):
  		df -h | egrep '^\/dev'
  		;;

  	(-info):
  	  uptime; date;\
  	  sysctl -n kern.securelevel;\
  	  sysctl -n net.inet.ip.forwarding;\
  	  sysctl -n net.inet6.ip6.forwarding || echo 'n/a';\
  	  sysctl -n kern.hostname;\
  	  uname -msr;\
  	  uname -p
  	  ;;

	  (-pfinfo):
	    pfctl -s info
	    ;;

	  (-pfmem):
	    pfctl -s memory
	    ;;

	  (-pfstates):
	    if [ "$4" != "" ]; then
	      pfctl -s state -v | perl -e  'while (<STDIN>) { if ($i++  < 2) { s/\n//; } else { $i = 0; } print $_; }' | egrep $4 | head -n $3
	    else
	      pfctl -s state -v | perl -e  'while (<STDIN>) { if ($i++  < 2) { s/\n//; } else { $i = 0; } print $_; }' | head -n $3
	    fi
	    ;;

		(-log):
		  if [ "$4" != "" ]; then
		    tcpdump -n -e -ttt -r /var/log/pflog $4 | tail $3
		  else
		    tcpdump -n -e -ttt -r /var/log/pflog | tail $3
		  fi
		  ;;

	  (-queues):
	    pfctl -s queue -v
	    ;;

	esac

else

  case $2 in

    (-df):
    	$1 df -h | egrep '^\/dev'
    	;;

    (-info):
      $1 "uptime; date;\
          sysctl -n kern.securelevel;\
          sysctl -n net.inet.ip.forwarding;\
          sysctl -n net.inet6.ip6.forwarding || echo 'n/a';\
          sysctl -n kern.hostname;\
          uname -msr;
          uname -p"
      ;;

    (-pfinfo):
      $1 pfctl -s info
      ;;
    
    (-pfmem):
      $1 pfctl -s memory
      ;;

    (-pfstates):
      if [ "$4" != "" ]; then
        $1 pfctl -s state -v | perl -e  'while (<STDIN>) { if ($i++  < 2) { s/\n//; } else { $i = 0; } print $_; }' | egrep $4 | head -n $3
      else
        $1 pfctl -s state -v | perl -e  'while (<STDIN>) { if ($i++  < 2) { s/\n//; } else { $i = 0; } print $_; }' | head -n $3
      fi
      ;;

    (-log):
      if [ "$4" != "" ]; then
        $1 "tcpdump -n -e -ttt -r /var/log/pflog $4 | tail $3"
      else
        $1 "tcpdump -n -e -ttt -r /var/log/pflog | tail $3"
      fi
      ;;

    (-queues):
      $1 pfctl -s queue -v
      ;;

    (-conntest):
      $1 echo "connected"
      ;;

  esac

fi
