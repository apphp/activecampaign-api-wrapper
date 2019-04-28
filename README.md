ApPHP ActiveCampaign API Wrapper
===============================

Thank you for choosing ApPHP ActiveCampaign API Wrapper
ActiveCampaign website: https://www.activecampaign.com/
ActiveCampaign API: https://developers.activecampaign.com/reference

INSTALLATION
------------
Please make sure the release file is unpacked under a web-accessible directory.
You will see the following files and directories:

    ActiveCampaign.php  API wrapper classdemo applications
	example.php         file with code example
    CHANGELOG           describing changes in every ApPHP release
	LICENSE             license file
    README              this file

REQUIREMENTS
------------
The minimum requirement by this class is that your Web server supports PHP 5.4.0 or
above. It has been tested with Apache HTTP server on Windows and Linux
operating systems.

The ApPHP Developer Team
------------
- https://www.apphp.com/
- http://www.apphpframework.com
- https://github.com/apphp/activecampaign-api-wrapper



USAGE (example):
------------
```PHP
<?php

use \Apphp\ActiveCampaign\ActiveCampaign;

require_lib('vendors/active_campaign/ActiveCampaign');

# Create new object
$config = array(
	'api_url'	=> '<API-URL>',
	'api_key'	=> '<API-KEY>',
);		
$activeCampaign = new ActiveCampaign($config);

$id = 3;
$contacts = $activeCampaign->getContact($id);

?>
```