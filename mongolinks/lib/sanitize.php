<?php

    function sanitize($data) 
    {
    	$rv = null;

		if ( isset($data) or !empty($data) )
		{
		  if ( is_numeric($data) )
		  {
            $rv = $data;		  
		  } 

		  $non_displayables = array(
			'/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
			'/%1[0-9a-f]/',             // url encoded 16-31
			'/[\x00-\x08]/',            // 00-08
			'/\x0b/',                   // 11
			'/\x0c/',                   // 12
			'/[\x0e-\x1f]/'             // 14-31
		  );

		  foreach ( $non_displayables as $regex ) $rv = preg_replace( $regex, '', $data );
		}

		return $rv;
	}
?>