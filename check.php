<?php

// Define the process names and corresponding commands
$processesAndCommands = array(
    "python3" => array("cd /home/u0123456789/domains/yourdomain.tld/public_html/Django-Project-Starter-Template/src/ && nohup python3 manage.py runserver & pkill -f php")
);

// Function to check if a process is running
function isProcessRunning($processName)
{
    $output = [];
    exec("pgrep -f $processName", $output);
    return !empty($output);
}

// Function to execute commands
function executeCommands($commands)
{
    foreach ($commands as $command) {
        shell_exec($command);
        echo "$command";
    }
}

// Check and execute commands if necessary
foreach ($processesAndCommands as $process => $commands) {
    if (!isProcessRunning($process)) {
        echo "$process is not running. Executing commands...\n";
        executeCommands($commands);
    }
}

?>
