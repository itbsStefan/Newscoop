#!/usr/bin/php
<?php
/**
 * @package Campsite
 *
 * @author Mugur Rus <mugur.rus@gmail.com>
 * @copyright 2008 MDLF, Inc.
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version $Revision$
 * @link http://www.campware.org
 */


function update_statistics($p_confDir)
{
    db_connect($p_confDir);

    $sessionDiff = 3600 * 12;
    // select sections older than 12 hours
    $sql = "SELECT ss.id, TIME_TO_SEC(TIMEDIFF(NOW(), ss.start_time)) AS session_time_diff,"
         . " MIN(TIME_TO_SEC(TIMEDIFF(NOW(), rq.last_request_time))) AS last_update_diff"
         . " FROM Sessions AS ss LEFT JOIN Requests AS rq ON ss.id = rq.session_id"
         . " WHERE TIME_TO_SEC(TIMEDIFF(NOW(), ss.start_time)) >= $sessionDiff GROUP BY ss.id";
    $sessRes = mysql_query($sql);
    while ($sessRow = mysql_fetch_array($sessRes, MYSQL_ASSOC)) {
        if ($sessRow['last_update_diff'] < 3600) {
            // if there was a request for this session less than one hour ago
            // do not process the session
            continue;
        }

        // select session requests (each requests identifies an object)
        $sessionId = $sessRow['id'];
        $sql = "SELECT * FROM Requests WHERE session_id = '"
             . mysql_escape_string($sessionId) . "'";
        $reqRes = mysql_query($sql);
        while ($reqRow = mysql_fetch_array($reqRes, MYSQL_ASSOC)) {
            // increment the request count for the object id
            $objectId = $reqRow['object_id'];
            $sql = "UPDATE RequestObjects SET request_count = LAST_INSERT_ID(request_count + 1)"
                 . " WHERE object_id = '" . mysql_escape_string($objectId) . "'";
            mysql_query($sql);
        }

        // delete the session requests
        $sql = "DELETE FROM Requests WHERE session_id = '"
             . mysql_escape_string($sessionId) . "'";
        mysql_query($sql);
        // delete the session
        $sql = "DELETE FROM Sessions WHERE id = '" . mysql_escape_string($sessionId) . "'";
        mysql_query($sql);
    }
}


function db_connect($p_conf_dir)
{
    is_valid_conf_dir($p_conf_dir);

    require_once($p_conf_dir . '/database_conf.php');
    mysql_connect($Campsite['DATABASE_SERVER_ADDRESS'],
                  $Campsite['DATABASE_USER'],
                  $Campsite['DATABASE_PASSWORD']) or
        die('Could not connect: ' . mysql_error());
    mysql_select_db($Campsite['DATABASE_NAME']);
} // fn db_connect


function is_valid_conf_dir($p_conf_dir)
{
    try {
        if (!is_dir($p_conf_dir)) {
            throw new Exception('Invalid configuration directory ' . $p_conf_dir);
        }
        return true;
    } catch (Exception $e) {
        print('Error: ' . $e->getMessage() . "\n");
        exit(1);
    }
} // fn isValidConfDir


function usage()
{
    printf("Usage: campsite-statistics [options]\n"
           ."Options:\n"
           ."\t--conf_dir=[configuration_dir]: Host name of the MySQL Server.\n"
           ."\t--help: Display this information.\n");
} // fn usage


// gets the arguments from command line, if any
if (is_array($GLOBALS['argv']) && !empty($GLOBALS['argv'][1])) {
    $option = explode('=', $GLOBALS['argv'][1]);
    if ($option[0] != '--conf_dir') {
        usage();
        exit(0);
    }

    $conf_dir = (!empty($option[1])) ? rtrim($option[1], '/') : '';
    if (empty($conf_dir)) {
        usage();
        exit(0);
    }
} else {
    $bin_dir = dirname(__FILE__);
    $conf_dir = preg_replace('/bin$/', 'conf', $bin_dir);
}


// runs the script
update_statistics($conf_dir);

?>
