<?php

namespace Sabre\CalDAV\Principal;

use Sabre\DAVACL;

/**
 * ProxyWrite principal interface
 *
 * Any principal node implementing this interface will be picked up as a 'proxy
 * principal group'.
 *
 * @copyright Copyright (C) fruux GmbH (https://fruux.com/)
 * @author Evert Pot (http://evertpot.com/)
 * @license http://sabre.io/license/ Modified BSD License
 */
interface IProxyWrite extends DAVACL\IPrincipal {

}
