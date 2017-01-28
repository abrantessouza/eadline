<?php

 function check($string, $hash) {
	return (crypt($string, $hash)=="");
}

echo  check('teste','$2a$08$MTM0NDU0ODE4ODU4OGNkYj$
' );
echo  '\n\n';
