phpMyBitcoin
============

Quick Install
-------------
1. Download the entire project, unzip/untar it and upload to your server
2. Create a MySQL database and user
3. Open install.php in your browser
4. Put in your MySQL information
5. Create the first user
6. Add nodes

Bitcoin Configuration
---------------------
In order to take full advantage of phpMyBitcoin you will need to run a Bitcoin
client that supports the listgenerated and listtransactions methods, and you
must be able to connect to the node's JSON-RPC interface.

I've created a custom version of Bitcoin that includes patches for
listgenerated, listtransactions and binds the JSON-RPC interface to all IPs
instead of just the loopback. You can find the source code here:

[http://github.com/aceat64/bitcoin-patchwork](http://github.com/aceat64/bitcoin-patchwork)

I also have pre-compiled binaries for Linux available:

[http://github.com/aceat64/bitcoin-patchwork/downloads](http://github.com/aceat64/bitcoin-patchwork/downloads)

Using The Vanilla Bitcoin Client
--------------------------------
If you would like to use phpMyBitcoin but don't want to compile or use a
modified client, you will need to use a relay of some kind so that phpMyBitcoin
can access the JSON-RPC interface.

Please note: Unless you have compiled your Linux kernel with IP_NF_NAT_LOCAL
you will not be able to use iptables to redirect requests to the loopback
interface.

If the remote server you are running Bitcoin on has a webserver with PHP
capabilites you can use the relay.php script (found in the scripts folder) to
relay connections to the loopback interface. Setup is very simpile, just drop
the relay.php script somewhere accessible from the internet. When adding the
node to phpMyBitcoin if your relay.php script is accessible at
"http://somesite.com/relay.php" your settings will be:

	Hostname: somesite.com
	Port: 80
	URI: relay.php

License
-------
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
