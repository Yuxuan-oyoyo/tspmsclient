<?php

/*{{{ v.150906.001 (0.0.1)

	Bitbucket webhook interface.

	Based on 'Automated git deployment' script by Jonathan Nicoal:
	http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/

	See README.md and CONFIG.php

	---
	Igor Lilliputten
	mailto: igor at lilliputten dot ru
	http://lilliputtem.ru/

}}}*/

// Initalize:
require_once('log.php');
require_once('bitbucket.php');

// Load config:
include('CONFIG.php');

// Let's go:
initLog(); // Initalize log variables
initPayload(); // Get posted data
fetchParams(); // Get parameters from bitbucket payload (REPO)
checkPaths(); // Check repository and project paths; create them if neccessary
placeVerboseInfo(); // Place verbose log information if specified in config
fetchRepository(); // Fetch or clone repository
checkoutProject(); // Checkout project into target folder