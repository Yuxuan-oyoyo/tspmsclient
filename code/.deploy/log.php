<?php

/*{{{ v.150906.001 (0.0.1)

	Logging module.

	---
	Igor Lilliputten
	mailto: igor at lilliputten dot ru
	http://lilliputtem.ru/

}}}*/

/*{{{ Global variables */

$_LOG_FILE = 'log.txt'; // log file name
$_LOG_ENABLED = true; // set to 'true' for enabling logging

/*}}}*/

function _LOG_CLEAR ()/*{{{*/
{
    if ( !empty($GLOBALS['_LOG_ENABLED']) ) {
        // file_put_contents($GLOBALS['_LOG_FILE'], "", LOCK_EX);
        // flush();
        unlink($GLOBALS['_LOG_FILE']);
    }
}/*}}}*/
function _LOG ($s)/*{{{*/
{
    if ( !empty($GLOBALS['_LOG_ENABLED']) ) {
        $datetime = date('Y.m.d H:i:s');
        file_put_contents($GLOBALS['_LOG_FILE'], $datetime."\t".$s."\n", FILE_APPEND | LOCK_EX);
        flush();
    }
}/*}}}*/
function _LOG_VAR ($s,$p)/*{{{*/
{
    echo $s.": ".print_r($p,true);
    _LOG($s.": ".print_r($p,true));
}/*}}}*/
function _ERROR ($s)/*{{{*/
{
    echo $s;
    _LOG('ERROR: '.$s);
}/*}}}*/