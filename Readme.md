##Installation

    git clone
    # update R3DAlign submodule
    git submodule init
    git submodule update
    cd R3DAlign
    # update FR3D submodule
    git submodule init
    git submodule update

##Configuration
+ CodeIgniter main config

        cp application/config/config_template.php application/config/config.php
        edit application/config/config.php

+ Email helper configuration

        cp application/config/email_template.php application/config/email.php
        edit application/config/email.php

+ Database connection

        cp application/config/database_template.php application/config/database.php
        edit application/config/database.php

+ Server-specific paths

        cp application/config/r3dalign_template.php application/config/r3dalign.php
        edit application/config/r3dalign.php

+ Queue management

        cp queue/r3dalign_queue_config_template.pl queue/r3dalign_queue_config.pl
        edit queue/r3dalign_queue_config.pl

+ Matlab

        cp matlab/importConfigTemplate.m matlab/importConfig.m
        edit matlab/importConfig.m

##Credits
The main computations are performed by [R3DAlign](https://github.com/BGSU-RNA/R3DAlign) and [FR3D](https://github.com/BGSU-RNA/FR3D).
This project also uses:

+ [jQuery](http://jquery.com)
+ [Handlebars](http://handlebarsjs.com/)
+ [CodeIgniter](http://ellislab.com/codeigniter)
+ [Twitter Bootstrap](http://twitter.github.com/bootstrap/)
+ [Jmol](http://jmol.org)