<?php

/**
 * @version     1.0.0
 * @package     com_firmen
 * @copyright   Copyright (C) 2013. Alle Rechte vorbehalten.
 * @license     GNU General Public License version 2 oder später; siehe LICENSE.txt
 * @author      Gaukeleier <gaukeleier@gmail.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Firmen records.
 */
class FirmenModelfirmens extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'adresseid', 'a.adresseid',
                'anrede', 'a.anrede',
                'firma', 'a.firma',
                'vorname', 'a.vorname',
                'nachname', 'a.nachname',
                'strasse', 'a.strasse',
                'nr', 'a.nr',
                'land', 'a.land',
                'plz', 'a.plz',
                'ort', 'a.ort',
                'tel', 'a.tel',
                'fax', 'a.fax',
                'email', 'a.email',
                'mobil', 'a.mobil',
                'auftragid', 'a.auftragid',
                'kategorieid', 'a.kategorieid',
                'geb', 'a.geb',
                'gebrem', 'a.gebrem',
                'gebremmail', 'a.gebremmail',
                'type', 'a.type',
                'bemerkung', 'a.bemerkung',
                'adressespracheid', 'a.adressespracheid',
                'homepage', 'a.homepage',
                'vsgkundentyp', 'a.vsgkundentyp',
                'exporttoldap', 'a.exporttoldap',
                'zusatz1', 'a.zusatz1',
                'position', 'a.position',
                'lastchange', 'a.lastchange',
                'bezirk', 'a.bezirk',
                'emailinvalid', 'a.emailinvalid',
                'titelprefix', 'a.titelprefix',
                'titelsuffix', 'a.titelsuffix',
                'abteilung', 'a.abteilung',
                'kundennummer', 'a.kundennummer',
                'as_praesentation', 'a.as_praesentation',
                'breite', 'a.breite',
                'laenge', 'a.laenge',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        

        // Load the parameters.
        $params = JComponentHelper::getParams('com_firmen');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__firmen` AS a');

        

        

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                
            }
        }

        


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
        return $items;
    }

}
