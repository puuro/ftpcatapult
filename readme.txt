FTP CATAPULT

A minimalistic version control system for Windows. Uses FTP to move changed files to a server. To be used with e.g. XAMPP. Requires PHP.

INSTALLATION

Move ftpcatapult folder to a location where you can run the PHP files. For example if you are using XAMPP it may be C:\xampp\htdocs\ftpcatapult. 

Create directories: 
C:\last_commit
C:\last_push

Edit conf.php and set values for the five variables. Local_path variable means path to your local repository, "c:/xampp/htdocs/" by default. Remote_path means path to your repository at server, for example "/public_html/" or "/". 

Initialize contents of the folders. local_path, remote_path, c:\last_commit and c:\last_push should have identical content(but "ftpcatapult" needs only be in one place.)

Ready!

USING FTP CATAPULT

You can use the program in browser by navigating to localhost/ftpcatapult. The program has two commands:

"commit": copies all files from "local_path" defined in conf.php to last_commit directory, excluding directories named "ftpcatapult" or "lib".

"push": Compare content of last_push and last_commit directories. If last_commit contains new or changed files or folders, Ftp Catapult uploads them to remote_path. After changes are uploaded copy content of last_commit to last_push. In short, this command uploads last commit to server.

NOTES

Ftp Catapult never deletes any files. 
Ftp Catapult never downloads any files from server.
"lib" folder is excluded from the process to speed up the program because it often contains libraries that are never changed. 
Test Ftp Catapult before real use! It is working on author's setup but the author can't give any guarantee about the program. 

