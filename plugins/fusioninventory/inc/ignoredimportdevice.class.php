<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the list of devices ignored on import.
 * Mean when device go in import rules, rules say "ignore import this device
 * because I don't want it"
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the list of devices ignored on import.
 * Mean when device go in import rules, rules say "ignore import this device
 * because I don't want it"
 */
class PluginFusioninventoryIgnoredimportdevice extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_ignoredimportdevice';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return __('Equipment ignored on import', 'fusioninventory');
   }



   /**
    * Get search function for the class
    *
    * @return array
    */
   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Agent', 'fusioninventory');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = __('Name');
      $tab[1]['massiveaction']  = false;

      $tab[2]['table']     = 'glpi_rules';
      $tab[2]['field']     = 'id';
      $tab[2]['name']      = __('Rule name', 'fusioninventory');
      $tab[2]['datatype']  = 'itemlink';
      $tab[7]['itemlink_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $tab[2]['massiveaction']  = false;

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'date';
      $tab[3]['linkfield'] = '';
      $tab[3]['name']      = __('Date');
      $tab[3]['datatype']  = 'datetime';
      $tab[3]['massiveaction']  = false;

      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'itemtype';
      $tab[4]['name']          = __('Item type');
      $tab[4]['massiveaction'] = false;
      $tab[4]['datatype']      = 'itemtypename';

      $tab[5]['table']     = 'glpi_entities';
      $tab[5]['field']     = 'completename';
      $tab[5]['name']      = __('Entity');
      $tab[5]['massiveaction']  = false;
      $tab[5]['datatype']  = 'dropdown';

      $tab[6]['table']           = $this->getTable();
      $tab[6]['field']           = 'serial';
      $tab[6]['name']            = __('Serial number');
      $tab[6]['datatype']        = 'string';
      $tab[6]['massiveaction']  = false;

      $tab[7]['table']          = $this->getTable();
      $tab[7]['field']          = 'uuid';
      $tab[7]['name']           = __('UUID');
      $tab[7]['datatype']       = 'string';
      $tab[7]['massiveaction']  = false;

      $tab[8]['table']           = $this->getTable();
      $tab[8]['field']           = 'ip';
      $tab[8]['name']            = __('IP');
      $tab[8]['datatype']        = 'string';
      $tab[8]['massiveaction']  = false;

      $tab[9]['table']           = $this->getTable();
      $tab[9]['field']           = 'mac';
      $tab[9]['name']            = __('MAC');
      $tab[9]['datatype']        = 'string';
      $tab[9]['massiveaction']  = false;

      $tab[10]['table']           = $this->getTable();
      $tab[10]['field']           = 'method';
      $tab[10]['name']            = __('Module', 'fusioninventory');
      $tab[10]['datatype']        = 'string';
      $tab[10]['massiveaction']  = false;

      $tab[11]['table']         = 'glpi_plugin_fusioninventory_agents';
      $tab[11]['field']         = 'name';
      $tab[11]['name']          = __('Agent', 'fusioninventory');
      $tab[11]['datatype']      = 'itemlink';
      $tab[11]['massiveaction'] = false;
      $tab[11]['itemlink_type'] = 'PluginFusioninventoryAgent';

      return $tab;
   }



   /**
    * Get search parameters for default search / display list
    *
    * @return array
    */
   static function getDefaultSearchRequest() {
      return array('sort'  => 3,
                   'order' => 'DESC');
   }

}

?>
