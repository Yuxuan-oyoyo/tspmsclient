<?php

/*{{{ v.151005.001 (0.0.2)

	Routines for work with bitbucket server, repositories and projects.

	Based on 'Automated git deployment' script by Jonathan Nicoal:
	http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/

	See README.md and CONFIG.php

	---
	Igor Lilliputten
	mailto: igor at lilliputten dot ru
	http://lilliputtem.ru/

}}}*/

/*{{{ *** Global variables */

define('DEFAULT_BRANCH', 'master');
define('DEFAULT_FOLDER_MODE', 0755);

$REPO = '';
$PAYLOAD = array ();

/*}}}*/

function initLog ()/*{{{ Initalizing log variables */
{
    global $CONFIG;

    if ( !empty($CONFIG['log']) ) {
        $GLOBALS['_LOG_ENABLED'] = true;
    }
    if ( !empty($CONFIG['logFile']) ) {
        $GLOBALS['_LOG_FILE'] = $CONFIG['logFile'];
    }
    if ( !empty($CONFIG['logClear']) ) {
        _LOG_CLEAR();
    }

    _LOG('*** '.$_SERVER['HTTP_X_EVENT_KEY'].' #'.$_SERVER['HTTP_X_HOOK_UUID'].' ('.$_SERVER['HTTP_USER_AGENT'].')');

}/*}}}*/
function initPayload ()/*{{{ Get posted data */
{
    global $PAYLOAD, $CONFIG, $PROJECTS;

    if ( isset($_POST['payload']) ) { // old method
        $PAYLOAD = $_POST['payload'];
    } else { // new method
        $PAYLOAD = json_decode(file_get_contents('php://input'));
    }

    if ( empty($PAYLOAD) ) {
        _ERROR("No payload data for checkout!");
        exit;
    }

}/*}}}*/
function fetchParams ()/*{{{ Get parameters from bitbucket payload now only (REPO) */
{
    global $REPO, $PAYLOAD, $CONFIG, $PROJECTS;

    // Get repository name:
    $REPO = $PAYLOAD->repository->name;
    if ( empty($PROJECTS[$REPO]) ) {
        _ERROR("Not found repository config for '$REPO'!");
        exit;
    }

}/*}}}*/
function checkPaths ()/*{{{ Check repository and project paths; create them if neccessary */
{
    global $REPO, $CONFIG, $PROJECTS;

    // Check for repositories folder path; create if absent
    if ( !is_dir($CONFIG['repositoriesPath']) ) {
        $mode = ( !empty($CONFIG['folderMode']) ) ? $CONFIG['folderMode'] : DEFAULT_FOLDER_MODE;
        if ( mkdir($CONFIG['repositoriesPath'],$mode,true) ) {
            _LOG("Creating repository folder '".$CONFIG['repositoriesPath']." (".decoct($mode).") for '$REPO'");
        }
        else {
            _ERROR("Error creating repository folder '".$CONFIG['repositoriesPath']." for '$REPO'! Exiting.");
            exit;
        }
    }

    // Check for current project folder; create if absent
    if ( !is_dir($PROJECTS[$REPO]['projPath']) ) {
        $mode = ( !empty($CONFIG['folderMode']) ) ? $CONFIG['folderMode'] : DEFAULT_FOLDER_MODE;
        if ( mkdir($PROJECTS[$REPO]['projPath'],$mode,true) ) {
            _LOG("Creating project folder '".$PROJECTS[$REPO]['projPath']." (".decoct($mode).") for '$REPO'");
        }
        else {
            _ERROR("Error creating project folder '".$PROJECTS[$REPO]['projPath']." for '$REPO'! Exiting.");
            exit;
        }
    }

}/*}}}*/
function placeVerboseInfo ()/*{{{ Place verbose log information -- if specified in config */
{
    global $REPO, $CONFIG, $PROJECTS;

    $repoPath = $CONFIG['repositoriesPath'].$REPO.'.git/';

    if ( $CONFIG['verbose'] ) {
        _LOG_VAR('CONFIG: ',$CONFIG);
        _LOG_VAR('REPO: ',$REPO);
        _LOG_VAR('repoPath: ',$repoPath);
        _LOG_VAR('$PROJECTS[$REPO]: ',$PROJECTS[$REPO]);
    }
}/*}}}*/
function fetchRepository ()/*{{{ Fetch or clone repository */
{
    global $REPO, $CONFIG, $PROJECTS;

    // Compose current repository path
    $repoPath = $CONFIG['repositoriesPath'].$REPO.'.git/';

    // If repository or repository folder are absent then clone full repository
    if ( !is_dir($repoPath) || !is_file($repoPath.'HEAD') ) {
        _LOG("Absent repository for '$REPO', cloning");
        exec('cd '.$CONFIG['repositoriesPath'].' && '.$CONFIG['gitCommand'].' clone --mirror git@bitbucket.org:'.$CONFIG['bitbucketUsername'].'/'.$REPO.'.git');
    }
    // Else fetch changes
    else {
        _LOG("Fetching repository '$REPO'");
        exec('cd '.$repoPath.' && '.$CONFIG['gitCommand'].' fetch');
    }

}/*}}}*/
function checkoutProject ()/*{{{ Checkout project into target folder */
{
    global $REPO, $CONFIG, $PROJECTS;

    // Compose current repository path
    $repoPath = $CONFIG['repositoriesPath'].$REPO.'.git/';

    // Checkout project files
    $branch = ( !empty($PROJECTS[$REPO]['branch']) ) ? $PROJECTS[$REPO]['branch']: DEFAULT_BRANCH;
    exec('cd '.$repoPath.' && GIT_WORK_TREE='.$PROJECTS[$REPO]['projPath'].' '.$CONFIG['gitCommand'].' checkout -f '.$branch);

    if ( !empty($PROJECTS[$REPO]['postHookCmd']) ) {
        exec('cd '.$PROJECTS[$REPO]['projPath'].' && '.$PROJECTS[$REPO]['postHookCmd']);
    }

    // Log the deployment
    $hash = rtrim( shell_exec('cd '.$repoPath.' && '.$CONFIG['gitCommand'].' rev-parse --short HEAD') );
    _LOG("Done, commit #".$hash);

}/*}}}*/
