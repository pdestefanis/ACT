#!/bin/bash
/usr/bin/setfacl -dR -m g:apache:rwx app/tmp
/usr/bin/setfacl -R -m g:apache:rwx app/tmp
/usr/bin/setfacl -m g:apache:rw app/config/options.php

