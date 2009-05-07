<?php
    /*
    RosCMS - ReactOS Content Management System
    Copyright (C) 2009  Danny G�tte <dangerground@web.de>

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
 * class Admin_Languages
 * 
 */
class Admin_Languages extends Admin
{



  /**
   * Interface to add new language
   *
   * @access protected
   */
  protected function showNew( )
  {
    echo_strip('
      <h2>add new Language</h2>
      <form onsubmit="return false;">
        <fieldset>
          <legend>Language Data</legend>
          <label for="lang_name">Name</label>
          <input id="lang_name" name="lang_name" maxlength="64" />
          <br />

          <label for="lang_short">Short Name</label>
          <input id="lang_short" name="lang_short" maxlength="8" /> (folder name, where the generated content is stored)
          <br />

          <label for="lang_org">Native Name</label>
          <input id="lang_org" name="lang_org" maxlength="64" />
          <br />

          <label for="lang_level">Language level</label>
          <select id="lang_level" name="lang_level">
            <option value="0">0 (processed last)</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10 (reserved for standard language)</option>
          </select>
        </fieldset>
        <button onclick="'."submitLanguageAdd()".'">Create new Language</button>
      </form>
    ');
  } // end of member function showNew




  /**
   * Backend to create a new language
   *
   * @access protected
   */
  protected function submitNew( )
  {
    // try to insert new language
    $stmt=&DBConnection::getInstance()->prepare("INSERT INTO ".ROSCMST_LANGUAGES." (name, name_short, name_original, level) VALUES (:name, :short, :org, :level)");
    $stmt->bindParam('name',$_POST['lang_name'],PDO::PARAM_STR);
    $stmt->bindParam('short',$_POST['lang_short'],PDO::PARAM_STR);
    $stmt->bindParam('org',$_POST['lang_org'],PDO::PARAM_STR);
    $stmt->bindParam('level',$_POST['lang_level'],PDO::PARAM_INT);

    // give the user a success or failure message
    if ($stmt->execute()) {
      echo_strip('New Lanaguage was created successfully');
    }
    else {
      echo_strip('Error, while creating new Language');
    }
  } // end of member function submitNew



  /**
   * Interface to search(within a list) languages
   *
   * @access protected
   */
  protected function showSearch( )
  {
    // list of available languages
    $stmt=&DBConnection::getInstance()->prepare("SELECT id, name, name_short, name_original FROM ".ROSCMST_LANGUAGES." ORDER BY name ASC");
    $stmt->execute();
    $x=0;
    while ($lang = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ++$x;
      echo_strip ('
        <tr id="trl'.($x).'" class="'.($x%2 ? 'odd' : 'even').'" onclick="'."editLanguage(".$lang['id'].")".'" onmouseover="'."hlRow(this.id,1)".'" onmouseout="'."hlRow(this.id,2)".'">
          <td>'.$lang['name_short'].'</td>
          <td>'.$lang['name'].'</td>
          <td>'.htmlspecialchars($lang['name_original']).'</td>
        </tr>');
    }
  } // end of member function showSearch



  /**
   * Backend for search languages
   *
   * @access protected
   */
  protected function submitSearch( )
  {
    // show edit / delete form, if entry was selected
    if ($_POST['lang'] > 0) {
      if ($_GET['for'] == 'edit') {
        self::showEdit();
      }
      elseif ($_GET['for'] == 'delete') {
        self::showDelete();
      }
    }

    // show search again
    else {
      self::showSearch();
    }
  } // end of member function submitSearch



  /**
   * Interface to Edit languages
   *
   * @access protected
   */
  protected function showEdit( )
  {
    // language information
    $stmt=&DBConnection::getInstance()->prepare("SELECT name, name_short, name_original, id, level FROM ".ROSCMST_LANGUAGES." WHERE id=:lang_id");
    $stmt->bindParam('lang_id',$_REQUEST['lang'],PDO::PARAM_INT);
    $stmt->execute();
    $lang = $stmt->fetchOnce(PDO::FETCH_ASSOC);

    echo_strip('
      <h2>edit Language</h2>
      <form onsubmit="return false;">
        <fieldset>
          <legend>Language Data</legend>
          <input type="hidden" name="lang_id" id="lang_id" value="'.$lang['id'].'" />

          <label for="lang_name">Name</label>
          <input id="lang_name" name="lang_name" maxlength="64" value="'.$lang['name'].'" />
          <br />

          <label for="lang_short">Short Name</label>
          <input id="lang_short" name="lang_short" maxlength="8" value="'.$lang['name_short'].'" /> (folder name, where the generated content is stored)
          <br />

          <label for="lang_org">Native Name</label>
          <input id="lang_org" name="lang_org" maxlength="64" value="'.$lang['name_original'].'" />
          <br />

          <label for="lang_level">Language level</label>
          <select id="lang_level" name="lang_level">
            <option value="0" '.($lang['level'] == 0 ? ' selected="selected"' : '').'">0 (processed last)</option>
            <option value="1" '.($lang['level'] == 1 ? ' selected="selected"' : '').'>1</option>
            <option value="2" '.($lang['level'] == 2 ? ' selected="selected"' : '').'">2</option>
            <option value="3" '.($lang['level'] == 3 ? ' selected="selected"' : '').'">3</option>
            <option value="4" '.($lang['level'] == 4 ? ' selected="selected"' : '').'">4</option>
            <option value="5" '.($lang['level'] == 5 ? ' selected="selected"' : '').'">5</option>
            <option value="6" '.($lang['level'] == 6 ? ' selected="selected"' : '').'">6</option>
            <option value="7" '.($lang['level'] == 7 ? ' selected="selected"' : '').'">7</option>
            <option value="8" '.($lang['level'] == 8 ? ' selected="selected"' : '').'">8</option>
            <option value="9" '.($lang['level'] == 9 ? ' selected="selected"' : '').'">9</option>
            <option value="10" '.($lang['level'] == 10 ? ' selected="selected"' : '').'">10 (reserved for standard language)</option>
          </select>
        </fieldset>
        <button onclick="'."submitLanguageEdit()".'">Edit Language</button>
      </form>
    ');
  } // end of member function showEdit



  /**
   * Backend for edit languages
   *
   * @access protected
   */
  protected function submitEdit( )
  {
    // try to insert new access list
    $stmt=&DBConnection::getInstance()->prepare("UPDATE ".ROSCMST_LANGUAGES." SET name=:name, name_short=:short, name_original=:org, level=:level WHERE id=:lang_id");
    $stmt->bindParam('name',$_POST['lang_name'],PDO::PARAM_STR);
    $stmt->bindParam('short',$_POST['lang_short'],PDO::PARAM_STR);
    $stmt->bindParam('org',$_POST['lang_org'],PDO::PARAM_STR);
    $stmt->bindParam('level',$_POST['lang_level'],PDO::PARAM_STR);
    $stmt->bindParam('lang_id',$_POST['lang_id'],PDO::PARAM_INT);

    // give the user a success or failure message
    if ($stmt->execute()) {
      echo 'Language was edited successfully';
    }
    else {
      echo 'Error, while editing Language';
    }
  } // end of member function submitEdit



  /**
   *
   *
   * @access protected
   */
  protected function showDelete( )
  {
    echo 'not supported';
  } // end of member function showDelete



  /**
   *
   *
   * @access protected
   */
  protected function submitDelete( )
  {
    echo 'not supported';
  } // end of member function submitDelete



} // end of Admin_Languages
?>
