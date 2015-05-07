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
defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

/**
 * Core Design Lock Article plugin
 *
 * @author		Daniel Rataj <info@greatjoomla.com>
 * @package		Core Design
 * @subpackage	Content
 */
class plgContentCDLockArticle extends JPlugin
{
    /**
     * Live path
     * @var string
     */
    private $livepath = '';

    /**
     * UI Theme
     * @var string
     */
    private $uitheme = 'ui-lightness';

    /**
     * DB instance
     * @var null
     */
    private $db = null;

    /**
     * JScriptegrator plugin instance
     * @var JScriptegrator
     */
    private $JScriptegrator	= null;

    /**
     * Joomla! Application
     * @var JApplication
     */
    private $JApplication = null;

    /**
     * Joomla! Document
     * @var JDocument
     */
    private $JDocument = null;

    /**
     * Joomla! JInput
     * @var JInput
     */
    private $JInput = null;

    /**
     * Joomla! user object
     * @var null
     */
    private $JUser = null;

    /**
     * State of source
     * @var bool
     */
    private $isLocked = false;

    /**
     * Layout
     * @var string
     */
    private $layout = 'default';

    /**
     * Row object
     * @var null
     */
    private $row = null;

    /**
     * Random string
     * @var string
     */
    private $random_id = '';

    /**
     * Form ACTION redirect URL
     * @var string
     */
    private $formActionURL = '';

    /**
     * Redirection URL
     * @var string
     */
    private $redirectionURL = '';

    /**
     * Source id
     * @var int
     */
    private $sourceid = 0;

    /**
     * Context
     * @var string
     */
    private $context = '';


    /**
     *  Constructor
     * @param object $subject
     * @param array $config
     */
    public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

    /**
     * Joomla! onContentPrepare
     * @param $context
     * @param $article
     * @param $params
     * @param int $page
     * @return bool|string
     * @throws Exception
     */
    public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
        // don't run this plugin in context of Smart Search
        if ($context === 'com_finder.indexer')
        {
            return true;
        }

        if (!$context) return false;

        $context_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'context';

        $context_file = explode('.', $context);

        $context_file_name = array_shift($context_file);

        $context_function = implode('_', $context_file);
        $classname = get_class() . '_context_' . $context_file_name;
        $context_file_path = $context_dir . DIRECTORY_SEPARATOR . $context_file_name . '.php';

        if (!JFile::exists($context_file_path))
        {
            return false;
        }

        require_once $context_file_path;

        if (!class_exists($classname))
        {
            return false;
        }

        $context_class = new $classname();

        if (!is_callable(array($context_class, 'getInstance')))
        {
            return false;
        }

        $context_instance = $context_class->getInstance($article);

        if (!is_callable(array($context_instance, $context_function . '_get_sourceid')))
        {
            return false;
        }

        $this->sourceid = (int)call_user_func(array($context_instance, $context_function . '_get_sourceid'));

        $this->context = $context_file_name;

        if (!$this->sourceid) return false;

		$this->uitheme = (string) $this->params->get( 'uitheme', 'ui-lightness' );

        $this->random_id = JUserHelper::genRandomPassword(5);

        $this->JApplication = JFactory::getApplication();
        $this->JInput = $this->JApplication->input;

        // Scriptegrator check
        if (!class_exists('JScriptegrator'))
        {
            $this->JApplication->enqueueMessage(JText::_('PLG_CONTENT_CDLOCKARTICLE_ERROR_ENABLE_SCRIPTEGRATOR'), 'error');
            return false;
        } else {
            $this->JScriptegrator = JScriptegrator::getInstance('2.5.x.2.3.1');
            $this->JScriptegrator->importLibrary(
                array(
                    'jQuery',
                    'jQueryUI' => array('uitheme' => $this->uitheme)
                )
            );
            if ($error = $this->JScriptegrator->getError())
            {
                $this->JApplication->enqueueMessage($error, 'error');
                return false;
            }
        }

        $this->JDocument = JFactory::getDocument();
        $this->JUser = JFactory::getUser();

        $this->livepath = JURI::root(true);
        $this->layout = $this->params->get('layout', 'default');

        $this->formActionURL = $this->JScriptegrator->buildURL('html', false, $this->_name);
        $this->redirectionURL = $this->JScriptegrator->buildURL('url', false, $this->_name);

		// include tables
		JTable::addIncludePath(array(
			dirname(__FILE__) . DS  . 'table')
		);

        $this->db = $this->dbInstance();

        $data = array();
        $data['sourceid'] = $this->sourceid;
        $data['context'] = $this->context;

        try
        {
            if (!$this->db->bind($data))
            {
                throw new Exception(JText::sprintf('PLG_CONTENT_CDLOCKARTICLE_ERROR_DB', __METHOD__ . '->bind()'), 500);
            }

            $row = $this->db->loadRow();

            if ($row instanceof Exception)
            {
                $this->JApplication->enqueueMessage($row->getMessage(), 'error');
                return false;
            }

            $this->isLocked = (isset($row->id) and $row->id > 0 ? true : false);

            $this->row = $row;
        }
        catch(Exception $e)
        {
            $this->JApplication->enqueueMessage($e->getMessage(), 'error');
            return false;
        }

        unset($data);


        if ($task = $this->JInput->get($this->_name . '_task', '', 'cmd'))
        {
            $response 	= array();
            $status 	= 'error';
            $content 	= '';

            if (is_callable(array($this, $task)))
            {
                $response = call_user_func(array($this, $task));

                if (!is_array($response))
                {
                    // only string, create a "success" array
                    $response = array('error', $response);
                }
                elseif(!$response)
                {
                    // empty array, success
                    $response = array('', 'success');
                }

                if (isset($response[0])) $content = trim($response[0]);
                if (isset($response[1])) $status = trim($response[1]);

                $response = array();
                $response['content'] = $content;
                $response['status'] = $status;

                @ob_end_clean(); // clean potencial buffer

                if (!headers_sent())
                {
                    // if headers not sent yet, make sure that the response is type of JSON
                    header('Vary: Accept');
                    if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false))
                    {
                        header('Content-type: application/json');
                    }
                    else
                    {
                        // for browsers not accepting JSON as an answer - Internet Explorer!
                        header('Content-type: text/html');
                    }
                }

                $this->JApplication->close(json_encode($response));
            }
        }

		// user is authorized to lock article
		if ($this->authorisedTo('manage'))
        {
            $article->text = $this->lockArticleTemplate() . $article->text;
		}
        else
        {
			// article is authorized already
			if ($this->isAuth()) return true;

			// check if there is a password for article
			if (!isset($this->row->id)) return true;

			// replace $article->text variable with password form

            $passform = '';

            // get template
            if ($layoutpath = $this->getLayoutPath('passform'))
            {
                ob_start();
                require $layoutpath;
                $passform .= $this->JScriptegrator->compressHTML(ob_get_contents());
                ob_end_clean();
            }

            $text_to_modify = $this->replaceVariables($passform);

            if ($article->introtext and isset($article->fulltext))
            {
                $article->text = $text_to_modify;
            }
		}

        $this->JDocument->addScript($this->livepath . '/plugins/content/' . $this->_name . '/tmpl/' . $this->layout . '/js/' . $this->_name . '.js');
        $this->JDocument->addStyleSheet($this->livepath . '/plugins/content/' . $this->_name . '/tmpl/' . $this->layout . '/css/' . $this->_name . '.css');

        // add CSS stylesheet for RTL languages
        if ($this->JDocument->direction === 'rtl')
        {
            $this->JDocument->addStyleSheet($this->livepath . '/plugins/content/' . $this->_name . '/tmpl/' . $this->layout . '/css/' . $this->_name . '_rtl.css');
        }

        $script_options = array();
        $script_options[] = 'livepath : "' . $this->livepath . '"';
        $script_options[] = 'name : "' . $this->_name . '"';
        $script_options[] = 'layout : "' . $this->layout . '"';

        $lang_array = array();
        $lang_array[] = 'PLG_CONTENT_CDLOCKARTICLE_PASSWORD : "' . JText::_('PLG_CONTENT_CDLOCKARTICLE_PASSWORD', true) . '"';
        $lang_array[] = 'PLG_CONTENT_CDLOCKARTICLE_PASSWORD2 : "' . JText::_('PLG_CONTENT_CDLOCKARTICLE_PASSWORD2', true) . '"';
        $lang_array[] = 'PLG_CONTENT_CDLOCKARTICLE_ERROR_PASSWORD_DO_NOT_MATCH : "' . JText::_('PLG_CONTENT_CDLOCKARTICLE_ERROR_PASSWORD_DO_NOT_MATCH', true) . '"';
        $lang_array[] = 'PLG_CONTENT_CDLOCKARTICLE_HEADER : "' . JText::_('PLG_CONTENT_CDLOCKARTICLE_HEADER', true) . '"';
        $lang_array[] = 'PLG_CONTENT_CDLOCKARTICLE_PASSWORD_TO_UNLOCK : "' . JText::_('PLG_CONTENT_CDLOCKARTICLE_PASSWORD_TO_UNLOCK', true) . '"';
        $lang_array[] = 'PLG_CONTENT_CDLOCKARTICLE_LOCK_ARTICLE : "' . JText::_('PLG_CONTENT_CDLOCKARTICLE_LOCK_ARTICLE', true) . '"';
        $lang_array[] = 'PLG_CONTENT_CDLOCKARTICLE_UNLOCK_ARTICLE : "' . JText::_('PLG_CONTENT_CDLOCKARTICLE_UNLOCK_ARTICLE', true) . '"';

        if ($lang_array)
        {
            $lang_options = 'language : { ';
            $lang_options .= implode(", ", $lang_array);
            $lang_options .= ' }';
            $script_options[] = $lang_options;
        }
        unset($lang_array);

        $js = "
        <!--
        jQuery(document).ready(function($){
            if ($.fn.$this->_name) {
                $('#$this->_name" . "_" . $this->random_id . "').$this->_name({
                    " . implode( ", ", $script_options ) . "
                });
            }
        });
        // -->";
        $this->JDocument->addScriptDeclaration($js);
	}
	
	/**
	 * Check password routine
	 * 
	 * @return void
	 */
	private function post_checkPassword()
	{
        // security token
        if (!$this->checkToken())
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_ERROR_INVALID_TOKEN'), 'error');
        }

        // missing password or password is blank from some reason
        if (!isset($this->row->password) or !$this->row->password)
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_CHECK_PASSWORD_MISSING_PASSWORD'), 'error');
        }

        $article_password = (string)$this->row->password;

		$password = $this->JInput->post->get($this->_name . '_articlepassword', '', 'string');
		
		// check and set password if allowed
		if ($article_password !== md5($password))
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_CHECK_PASSWORD_ERROR_WRONG_PASSWORD'), 'error');
        }

        $this->setAuthArticle();

        return array();
	}

    /**
     * Lock Article
     * @return array
     * @throws Exception
     */
    private function post_lock()
	{
        // security token
        if (!$this->checkToken())
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_ERROR_INVALID_TOKEN'), 'error');
        }
        // authorization
        if (!$this->authorisedTo('manage'))
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_ERROR_NOT_AUTHORIZED'), 'error');
        }
		
		$data = array(
            $this->_name . '_sourceid' => 'int',
            $this->_name . '_password' => 'string',
            $this->_name . '_headertext' => 'string',
            $this->_name . '_context' => 'cmd'
        );

        $data = $this->JScriptegrator->sanitizeRequest($data, 'post', '_');

		if (!isset($data['password']) or !$data['password'])
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_NO_PASSWORD'), 'error');
		}

		$data['password'] = md5($data['password']);
		$data['lockedby'] = $this->JUser->get('id', 0 );

        try
        {
            if (!$this->db->bind($data))
            {
                throw new Exception(JText::sprintf('PLG_CONTENT_CDLOCKARTICLE_ERROR_DB', __METHOD__ . '->bind()'), 500);
            }

            if (!$this->db->store())
            {
                throw new Exception(JText::sprintf('PLG_CONTENT_CDLOCKARTICLE_ERROR_DB', __METHOD__ . '->store()'), 500);
            }

        }
        catch(Exception $e)
        {
            return array($e->getMessage(), 'error');
        }

        return array();
	}

    /**
     * Unlock Article
     * @return array
     * @throws Exception
     */
    private function post_unlock()
	{
		// security token
        if (!$this->checkToken())
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_ERROR_INVALID_TOKEN'), 'error');
        }
        // authorization
		if (!$this->authorisedTo('manage'))
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_ERROR_NOT_AUTHORIZED'), 'error');
        }

        if (!isset($this->row->id))
        {
            return array(JText::_('PLG_CONTENT_CDLOCKARTICLE_UNLOCK_ARTICLE_ERROR_NOT_LOCKED'), 'error');
        }

        $data = array(
            $this->_name . '_sourceid' => 'int',
            $this->_name . '_context' => 'cmd'
        );

        $data = $this->JScriptegrator->sanitizeRequest($data, 'post', '_');

        try
        {
            if (!$this->db->bind($data))
            {
                throw new Exception(JText::sprintf('PLG_CONTENT_CDLOCKARTICLE_ERROR_DB', __METHOD__ . '->bind()'), 500);
            }

            if (!$this->db->deleteRow())
            {
                throw new Exception(JText::sprintf('PLG_CONTENT_CDLOCKARTICLE_ERROR_DB', __METHOD__ . '->delete()'), 500);
            }

        }
        catch(Exception $e)
        {
            return array($e->getMessage(), 'error');
        }

        return array();
	}

    /**
     * Lock access
     * @param array $permissions
     * @return bool
     */
    private function authorisedTo($permissions = array('manage'))
	{
        $actions =
            array(
                'manage'
            );

        if (!$permissions) return false;

        // make sure it's array
        if (!is_array($permissions))
        {
            $permissions = (array) $permissions;
        }

        // unknown permission
        if(!array_intersect($permissions, $actions)) return false;

        $user_groups = $this->JUser->get('groups', array());

        $allowed_count = 0;
        foreach($permissions as $permission)
        {
            $authorised_to = (array) $this->params->get('permission_to_' . $permission, null);

            // user not logged in and permission is not "send_image"
            if ($this->JUser->get('guest'))
            {
                break;
            }

            // exception for super admin group
            if ($this->JUser->authorise('core.admin'))
            {
                ++$allowed_count; // increase permission count
                break;
            }

            // check groups
            if (array_intersect($authorised_to, $user_groups))
            {
                ++$allowed_count; // increase permission count
            }
        }

        // no permission
        if (!$allowed_count)
        {
            return false;
        }

        return true;
	}
	
	/**
	 * Check if article is authorized
	 * 
	 * @return boolean		True, if article is authorized.
	 */
	private function isAuth()
	{
		$session = JFactory::getSession();
		
		$sessionname = 'CDLockArticle';
		
		$allowed = $session->get($sessionname, array());
		
		// prevent already existing article id in session
		if (in_array((int)$this->sourceid, $allowed)) return true;
		
		return false;
	}
	
	/**
	 * Set authorized article to session
	 * 
	 * @return void
	 */
	private function setAuthArticle()
	{
		$session = JFactory::getSession();
		
		$sessionname = 'CDLockArticle';
		
		$allowed = $session->get($sessionname, array());
		
		// prevent already existing article id in session
		if (in_array((int) $this->sourceid, $allowed)) return false;
		
		array_push($allowed, (int)$this->sourceid);
		
		$session->set($sessionname, $allowed);
		
	}
	
	/**
	 * Lock article template
	 * 
	 * @return string		Lock article template.
	 */
	private function lockArticleTemplate()
	{
		$tmpl = '';
		
		// get template
		if ($layoutpath = $this->getLayoutPath('lockarticle'))
        {
			ob_start();
				require $layoutpath;
				$tmpl .= $this->JScriptegrator->compressHTML(ob_get_contents());
			ob_end_clean();
		}
		return $this->replaceVariables($tmpl);
	}
	
	/**
	 * Global close wrapper
	 *
	 * @param $msg		message to display
	 *
	 * @return void
	 */
	private function close($msg = '')
	{
		$this->JApplication->close($msg);
	}

    /**
     * Get Layout
     *
     * @param 		$file
     * @return 		string
     */
    private function getLayoutPath($file = '')
    {
        if (!$file) return false;

        $layout = $this->params->get('layout', 'default');
        $type = 'html';

        if ((int) $this->JInput->get->get('print', 0, 'int'))
        {
            $type = 'print';
        }

        $tmpldir = dirname(__FILE__) . DS  . 'tmpl' . DS . $layout;

        $filepath = $tmpldir . DS . $file . '.' . $type .  '.php';

        if ($type !== 'html' and !JFile::exists($filepath))
        {
            $type = 'html';
            $filepath = $tmpldir . DS . $file . '.' . $type .  '.php';
        }

        if (!JFile::exists($filepath))
        {
            return false;
        }

        return $filepath;
    }

	/**
	 * Load DB instance
	 * 
	 * @return 		A database object
	 */
	private function dbInstance($instance = '')
	{
		return JTable::getInstance('CDLockArticle' . $instance);
	}

    /**
     * Check token wrapper
     *
     * @return array|boolean
     */
    private function checkToken()
    {
        if($this->JInput->get(JSession::getFormToken(), '', 'alnum'))
        {
            return true;
        }
        return false;
    }

    /**
     * Replace variables in template
     * @param 	string	$string
     * @return 	string
     */
    private function replaceVariables($string = '')
    {
        if (!$string) return false;

        // str_replace template variables
        $str = $replace = array();

        // login link
        $str[] = '{$name}';
        $replace[] = $this->_name;

        return str_replace($str, $replace, $string);
    }
}