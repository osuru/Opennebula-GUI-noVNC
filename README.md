# Opennebula Alternative GUI
How to install

git clone git://github.com/osuru/.git
mv Opennebula-GUI-noVNC mysuperserver


Edit settings.php

We needed: 
1 Opennebula-sunstone (we only need novnc) or websockfy
 apt-get install opennebula-sunstone
 chmod 777 /var/lib/one/sunnstone_tokens
2 apt-get install php5-xmlrpc && service apace2 restart
 

Access http://YouHost/path_to_project/mysuperserver

