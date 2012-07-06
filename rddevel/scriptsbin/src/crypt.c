#include <stdio.h>
#include <stdlib.h>
char *crypt();
char *saltrange = "abcdefghijklmnopqrstuvwxyzABCDEFGIJKLMNOPQRSTUVWXYZ01234456789./";
char salt[3];

main(argc, argv)
int argc;
char **argv;
{
char psw_crypt[13 + 1];

srand((unsigned)time((time_t *) NULL));

salt[2]=0;
salt[1]=saltrange[rand()%64];
salt[0]=saltrange[rand()%64];


if ( argc < 2 ) {
        printf ("Usage: crypt <string>\n");
        exit (1);
        }
strcpy ( psw_crypt, crypt (argv[1], salt) );
printf ( "%s\n", psw_crypt );
} 
