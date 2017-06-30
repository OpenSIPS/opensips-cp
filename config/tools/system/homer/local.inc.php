<?php
/*
 * Copyright (C) 2016 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
###############################################################################


# the HTTP URL where Homer WEB portal is installed. Do not use IPs
# (use FQDN) as otherwise the cookie transfer between the 2 sites (CP and
# Homer) will not work anymore.
# IMPORTANT: the CP and Homer portal must be under the common subdomain !
#   (still, the portals can be installed on different servers / IPs)
#
# Examples:
#   = "https://homer.mydomain.com"
#   = "//homer.mydomain.com"  # this will preserv the protocol (http or https)
#                             # when jumping from CP to Homer.
#                             # IMPORTANT: if you do this, do not force a port
#                             # in URL as you will get a conflict between
#                             # HTTP and HTTPS (over the same port)
#	= "https://homer.mydomain.com:8080"
#	= "//homer.mydomain.com:8080"  # this is BROKEN as both HTTP and HTTPS
#                                  # will be forced over the same port
$homer_URL = "http://homer.opensips.org";

# the authentication method to be used against HOMER. It can be:
# * cookie - the auth ID will be passed as an HTTP cookie to the
#            HOMER portal ; this will require to set the 
#            $common_subdomain too !
# * get    - the auth ID will be passed as an GET parameter to the
#            HOMER portal; nothing more is required; this is a 
#            much more flexible approach.
$homer_auth_method = "get";

# the common HTTP subdomaim shared between the CP URL and HOMER URL.
# This is used for cookie transfer and must include at least 2 levels
# (.com is not considered a valid subdomain).
# Example:
#     CP URL : www.mydomain.com  or cp.mydomain.com
#     HOMER URL: homer.mydomain.com
#     Common Subdomain :  .mydomain.com
$common_subdomain = ".opensips.org";

?>
