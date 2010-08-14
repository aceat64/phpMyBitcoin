phpMyBitcoin
============

Installation
------------
1. Download the entire project, unzip/untar it and upload to your server
2. Create a MySQL database and user
3. Modify the app/config/database.php file and put in the database name, username and password
4. Import the basic MySQL database from the file app/config/schema/database.sql
5. Modify the app/config/core.php file and replace the default Security.salt and Security.cipherSeed values
6. Login to with the user "admin" and password "password". (THIS DOES NOT WORK AT THE MOMENT)
7. Change the admin password or create a new user then delete the admin user.

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
