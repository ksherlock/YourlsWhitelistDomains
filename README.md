YourlsWhiteListDomains

A fork of  https://github.com/Panthro/YourlsWhitelistDomains which was a fork of https://github.com/LudoBoggio/YourlsBlacklistIPs.

Changes:

* white list does not apply if logged in as admin
* more accurate white list checking
* subdomains of white list entries are allowed
* whitelist is stored internally as an array of strings instead of a single string.

Previous README contents:


Plugin for Yourls allowing only whitelisted domains

This plugin is intended to be used with YOURLS (cf. http://yourls.org)

It has been tested on YOURLS v1.5 and v1.5.1

Current version is 1.0

Contact : panthro[dot]rafael[at]gmail[dot]com

INSTALL :
- In /user/plugins, create a new folder named whitelist-domains
- In this new directory, copy the plugin.php file from this repository
- Go to the Plugins administration page and activate the plugin

You will see in the admin section a new admin page where you can add the domain addresses to the whitelist.

Enter one domain in each line, please, remove the www when adding a new domain, the code works on both.

Thanks to https://github.com/LudoBoggio for the YourlsBlacklistIPs which was the base for this one:

https://github.com/LudoBoggio/YourlsBlacklistIPs
