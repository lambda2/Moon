<?php

/**
 * Manages all the security function who can
 * help the user to prevent security failures.
 */


function md5sum($data)
{
	return hash('md5', $data);
}

function sha1sum($data)
{
	return hash('sha1', $data);
}

function sha256sum($data)
{
	return hash('sha256', $data);
}

function encrypt($data,$algo='sha1',$salt='')
{
	return hash($algo,$salt.$data.$salt);
}


?>
