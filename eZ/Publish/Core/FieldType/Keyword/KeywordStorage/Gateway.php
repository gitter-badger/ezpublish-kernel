<?php
/**
 * File containing the KeywordStorage Gateway
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\FieldType\Keyword\KeywordStorage;

abstract class Gateway
{
    /**
     * Returns the active connection
     *
     * @return \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler
     * @throws \RuntimeException if no connection has been set, yet.
     */
    protected function getConnection()
    {
        if ( $this->dbHandler === null )
        {
            throw new \RuntimeException( "Missing database connection." );
        }
        return $this->dbHandler;
    }

    /**
     * @see \eZ\Publish\SPI\FieldType\FieldStorage::storeFieldData()
     */
    abstract public function storeFieldData( Field $field );

    /**
     * Sets the list of assigned keywords into $field->value->externalData
     *
     * @param Field $field
     * @return void
     */
    abstract public function getFieldData( Field $field );
}

