<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/* generates a mostly-random character sequence;
 * based on http://www.stonehenge.com/merlyn/WebTechniques/col61.html
 */
function salt() {
  return md5(
    md5(
      microtime() .
      spl_object_hash(new stdClass()) .
      mt_rand() .
      getmypid()));
}

