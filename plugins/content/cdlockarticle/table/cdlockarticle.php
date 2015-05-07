<?php
/**
 * Core Design Lock Article plugin for Joomla! 2.5
 * @author		Daniel Rataj, <info@greatjoomla.com>
 * @package		Joomla
 * @subpackage	Content
 * @category	Plugin
 * @version		2.5.x.2.0.5
 * @copyright	Copyright (C) 2007 - 2013 Great Joomla!, http://www.greatjoomla.com
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL 3
 * 
 * This file is part of Great Joomla! extension.   
 * This extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined( '_JEXEC' ) or die;

/**
 * Core Design Photo Contest table
 *
 * @package 		Joomla.Framework
 * @subpackage		Table
 * @since			1.0
 */
class JTableCDLockArticle extends JTable
{
	/** @var int Primary key */
	public $id = null;
	
	/** @public int */
	public $sourceid = null;
	
	/** @public string */
	public $context = null;

	/** @public string */
	public $password = null;

	/** @public string */
	public $headertext = null;
	
	/** @public int */
	public $lockedby = null;

    /**
     * A database connector object
     * @param string $db
     */
    public function __construct(&$db)
    {
		parent::__construct( '#__cdlockarticle', 'id', $db );
	}

    /**
     * Load row based on source ID
     * @return Exception|object
     * @throws Exception
     */
    public function loadRow()
	{
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('sourceid') . ' = ' . (int)$this->sourceid);
        $query->where($this->_db->quoteName('context') . ' LIKE ' . $this->_db->quote($this->context));

        $this->_db->setQuery( $query );

        try
        {
            if (!$this->_db->query())
            {
                throw new Exception();
            }
        }
        catch(Exception $e)
        {
            return $e;
        }
		
		return $this->_db->loadObject();
	}

    /**
     * Delete row
     * @return bool|Exception
     * @throws Exception
     */
    public function deleteRow()
    {
        $query = $this->_db->getQuery(true);
        $query->delete($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('sourceid') . ' = ' . (int)$this->sourceid);
        $query->where($this->_db->quoteName('context') . ' LIKE ' . $this->_db->quote($this->context));

        $this->_db->setQuery($query);

        try
        {
            if (!$this->_db->query())
            {
                throw new Exception();
            }
        }
        catch(Exception $e)
        {
            return $e;
        }

        return true;
    }
}