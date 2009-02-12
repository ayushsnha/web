<?php
    /*
    RosCMS - ReactOS Content Management System
    Copyright (C) 2007  Klemens Friedl <frik85@reactos.org>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
    */


/**
 * class HTML_CMS_Maintain
 * 
 * @package html
 * @subpackage cms
 */
class HTML_CMS_Maintain extends HTML_CMS
{


  /**
   * setup branch info, register css/js, check for access
   *
   * @access public
   */
  public function __construct( )
  {
    Login::required();

    $this->branch = 'maintain';

    // register css & js files
    $this->register_css('cms_maintain.css');
    $this->register_js('cms_maintain.js');

    // check if user has rights for this area
    if (!ThisUser::getInstance()->hasAccess('maintain')) {
      die('Not enough rights to get into this area');
    }

    parent::__construct();
  } // end of constructor



  protected function body( )
  {
    echo_strip('
      <p><a href="javascript:optimizeDB()">Optimize Database Tables</a></p>
      <br />
      <p><a href="javascript:rebuildDepencies()">Rebuild Depency Tree</a></p>

      <div>
        <label for="textfield">Entry-Name:</label>
        <input name="textfield" type="text" id="textfield" size="20" maxlength="100" />
        <select id="txtaddentrytype" name="txtaddentrytype">
          <option value="page" selected="selected">Page</option>
          <option value="dynamic">Dynamic Page</option>
          <option value="content">Content</option>
          <option value="template">Template</option>
          <option value="script">Script</option>
          <option value="system">System</option>
        </select>
        <select id="txtaddentrylang" name="txtaddentrylang">');

    // display languages
    $stmt=&DBConnection::getInstance()->prepare("SELECT id, name FROM ".ROSCMST_LANGUAGES." WHERE level > 0 ORDER BY name ASC");
    $stmt->execute();
    while ($language=$stmt->fetch(PDO::FETCH_ASSOC)) {
      echo '<option value="'.$language['id'].'"'.($language['id']==Language::getStandardId() ? ' selected="selected"' : '').'>'.$language['name'].'</option>';
    }
    echo_strip('
        </select>
        <button name="entryupdate" onclick="generatePage()">generate</button>
      </div>

      <p><a href="javascript:generateAllPages()">Generate All Pages</a></p>
      <div id="maintainarea" style="border: 1px dashed red;display:none;"></div>
      <img id="ajaxloading" style="display:none;" src="images/ajax_loading.gif" width="13" height="13" alt="" />
      <br />');

    if (ThisUser::getInstance()->hasAccess('logs')) {

      // display logs
      echo_strip('
        <br />
        <h2>RosCMS Global Log</h2>
        <h3>High Security Log - '.date('Y-W').'</h3>
        <textarea name="logviewerhigh" cols="75" rows="7">');echo Log::read('high');echo_strip('</textarea><br />
        <br />
        <h3>Medium Security Log - '.date('Y-W').'</h3>
        <textarea name="logviewermed" cols="75" rows="5">');echo Log::read('medium');echo_strip('</textarea><br />
        <br /><h3>Low Security Log - '.date('Y-W').'</h3>
        <textarea name="logviewerlow" cols="75" rows="3">');echo Log::read('low');echo_strip('</textarea><br />
        <br />
        <br />
        <br />
        <h2>RosCMS Generator Log</h2>
        <h3>High Security Log - '.date('Y-W').'</h3>
        <textarea name="logviewerhigh2" cols="75" rows="7">');echo Log::read('high','generate');echo_strip('</textarea><br />
        <br />
        <h3>Medium Security Log - '.date('Y-W').'</h3>
        <textarea name="logviewermed2" cols="75" rows="5">');echo Log::read('medium','generate');echo_strip('</textarea><br />
        <br />
        <h3>Low Security Log - '.date('Y-W').'</h3>
        <textarea name="logviewerlow2" cols="75" rows="3">');echo Log::read('low','generate');echo_strip('</textarea><br />
        <br />
        <br />
        <br />
        <h2>RosCMS Language Group Logs</h2>');

      // language specific logs
      $stmt=&DBConnection::getInstance()->prepare("SELECT id, name FROM ".ROSCMST_LANGUAGES." ORDER BY name ASC");
      $stmt->execute();
      while ($language = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo_strip('
          <h3>'.$language['name'].'</h3>
          <h4>High Security Log - '.date('Y-W').'</h4>
          <textarea name="logviewerhigh'.$language['id'].'" cols="75" rows="5">');echo Log::read('high', $language['id']);echo_strip('</textarea><br />
          <br />
          <h4>Medium Security Log - '.date('Y-W').'</h4>
          <textarea name="logviewermed'.$language['id'].'" cols="75" rows="4">');echo Log::read('medium', $language['id']);echo_strip('</textarea><br />
          <br />
          <h4>Low Security Log - '.date('Y-W').'</h4>
        <textarea name="logviewerlow'.$language['id'].'" cols="75" rows="3">');echo Log::read('low', $language['id']);echo_strip('</textarea><br />
        <br />
        <br />');
      }
    } // end of ros_admin only
  } // end of member function body



} // end of HTML_CMS_Maintain
?>