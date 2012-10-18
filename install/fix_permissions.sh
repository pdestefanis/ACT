#!/bin/bash
# TO DO: Add the base directory

# ACL-based permissions

/usr/bin/setfacl -dR -m g:apache:rwx app/tmp
/usr/bin/setfacl -R -m g:apache:rwx app/tmp
/usr/bin/setfacl -m g:apache:rw app/config/options.php

# The POSIX version
/bin/chown -R root.apache *
/bin/chmod -R g+w app/tmp
/bin/chmod -R g+w app/config/options.php

