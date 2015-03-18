<?php

  function sanitize($data) 
  {
    $rv = null;

    if(!empty($data) && is_string($data))
    {
      $no_inject = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $data);
      $rv = strip_tags($no_inject);
    }

    return $rv;
  }
?>
