CC= /usr/bin/gcc
PHP= /usr/bin/php
APACHE_GID=33
APACHE_UID=33

RDDEVEL_UID = 1002

CC= gcc

prefix= /usr/local/rddevel/

COMMON_FLAGS= -DPREFIX="\"$(prefix)\"" -DPHP="\"$(PHP)\"" -DAPACHE_UID=$(APACHE_UID) -DAPACHE_GID=$(APACHE_GID) -DRDDEVEL_UID=$(RDDEVEL_UID)
WRAPPER_FLAGS= -DHTTP_GID=$(APACHE_GID)

OPT= -g -O2
DEFS= -DHAVE_STRERROR=1 -DHAVE_SETREGID=1 -DHAVE_SYSLOG=1 -DSTDC_HEADERS=1 -DHAVE_SYSLOG_H=1 -DGETGROUPS_T=gid_t -DHAVE_VSNPRINTF=1 
LIBS=		

CFLAGS= -g -O2 $(OPT) $(DEFS) $(LIBS)


all: common.o wrapper.o
	rm -f ../wrapper
	$(CC) -o ../wrapper  common.o wrapper.o; \
	chmod u+s,g+s ../wrapper
	chown rddevel:rddevel ../wrapper

wrapper.o: common.h common.c Makefile
	$(CC) -c -I. $(CFLAGS) $(WRAPPER_FLAGS) wrapper.c

common.o: common.h common.c Makefile
	$(CC) -c -I. $(CFLAGS) $(COMMON_FLAGS) common.c
