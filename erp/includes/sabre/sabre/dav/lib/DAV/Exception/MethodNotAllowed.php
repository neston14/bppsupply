<?php

namespace Sabre\DAV\Exception;

use Sabre\DAV;

/**
 * MethodNotAllowed
 *
 * The 405 is thrown when a client tried to create a directory on an already existing directory
 *
 * @copyright Copyright (C) fruux GmbH (https://fruux.com/)
 * @author Evert Pot (http://evertpot.com/)
 * @license http://sabre.io/license/ Modified BSD License
 */
class MethodNotAllowed extends DAV\Exception {

    /**
     * Returns the HTTP statuscode for this exception
     *
     * @return int
     */
    function getHTTPCode() {

        return 405;

    }

    /**
     * This method allows the exception to return any extra HTTP response headers.
     *
     * The headers must be returned as an array.
     *
     * @param \Sabre\DAV\Server $server
     * @return array
     */
    function getHTTPHeaders(\Sabre\DAV\Server $server) {

        $methods = $server->getAllowedMethods($server->getRequestUri());

        return [
            'Allow' => strtoupper(implode(', ', $methods)),
        ];

    }

}
