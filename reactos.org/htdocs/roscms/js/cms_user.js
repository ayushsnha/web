


/**
 * performs a new search
 */
function getUser( )
{
  var soptckd = '';

  // only start with more than two chars
  if (document.getElementById('textfield').value.length > 2) {

    // get request type
    if (document.getElementById('searchopt1').checked) soptckd = 'accountname';
    if (document.getElementById('searchopt2').checked) soptckd = 'fullname';
    if (document.getElementById('searchopt3').checked) soptckd = 'email';
    if (document.getElementById('searchopt4').checked) soptckd = 'website';
    if (document.getElementById('searchopt5') && document.getElementById('searchopt5').checked) soptckd = 'language';

    makeRequest('?page=backend&type=user&subtype=usrtbl&show=list&phrase='+encodeURIComponent(document.getElementById('textfield').value)+'&option='+encodeURIComponent(soptckd));
  }
}



/**
 * requests user details
 *
 * @param int user_id details to this user are requested
 */
function getUserDetails( user_id )
{
  makeRequest('?page=backend&type=user&subtype=usrtbl&show=detail&user='+encodeURIComponent(user_id));
}



/**
 * add a new membership to a group
 *
 * @param int user_id affected user
 * @param string group_id group, where membership is added to
 */
function addMembership( user_id, group_id )
{
  makeRequest('?page=backend&type=user&subtype=usrtbl&show=detail&action=addmembership&user='+encodeURIComponent(user_id)+'&group='+encodeURIComponent(group_id));
}



/**
 * deletes a membership from a group
 *
 * @param int userid affected user
 * @param string member_id affected group
 */
function delMembership( user_id, member_id )
{
  var uf_check = confirm("Be careful! \n\nDo you want to delete this membership?");

  if (uf_check == true) {
    makeRequest('?page=backend&type=user&subtype=usrtbl&show=detail&action=delmembership&user='+encodeURIComponent(user_id)+'&group='+encodeURIComponent(member_id));
  }
}



/**
 * updates the users language setting
 *
 * @param int userid affected user
 * @param string membid new language
 */
function updateUserLang( user_id, language )
{
  var uf_check = confirm("Do you want to continue?");

  if (uf_check == true) {
    makeRequest('?page=backend&type=user&subtype=usrtbl&show=detail&action=updateusrlang&user='+encodeURIComponent(user_id)+'&lang='+encodeURIComponent(language));
  }
}



/**
 * make a new AJAX request
 *
 * @param string url requested url
 * @return bool
 */
function makeRequest( url )
{
  var http_request = false;
  document.getElementById('ajaxloading').style.display = 'block';

  if (window.XMLHttpRequest) { // Mozilla, Safari,...
    http_request = new XMLHttpRequest();
  }
  else if (window.ActiveXObject) { // IE
    try {
      http_request = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        http_request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {}
    }
  }

  if (!http_request) { // stop if browser doesn't support AJAX
    alert('Cannot create an XMLHTTP instance. \nMake sure that your browser does support AJAX. \nMake sure that your browser does support AJAX. \nTry out IE 5.5+ (with ActiveX enabled), IE7+, Mozilla, Opera 9+ or Safari 3+.');
    return false;
  }

  if (http_request.overrideMimeType) {
    http_request.overrideMimeType('text/html');
  }

  // inserts request results
  http_request.onreadystatechange = function()
  {
    try {
      if (http_request.readyState == 4) {
        if (http_request.status == 200) {
          document.getElementById('ajaxloading').style.display = 'none';
          document.getElementById('userarea').innerHTML = http_request.responseText;
        }
        else {
          alert('There was a problem with the request ['+http_request.status+' / '+http_request.readyState+']. \n\nA client (browser) or server problem. Please check and try to update your browser. \n\nIf this error happens more than once or twice, contac the website admin.');
        }
      }
    }
    catch( e ) {
      alert('Caught Exception: ' + e.description +'\n\nIf this error occur more than once or twice, please contact the website admin with the exact error message. \n\nIf you use the Safari browser, please make sure you run the latest version.');
    }
  };
  http_request.open('GET', url, true);
  http_request.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");	// Bypass the IE Cache
  http_request.send(null);
  return true;
}



/**
 * enables or disables useraccounts
 *
 * @param int user_id affected user
 * @param string enable 'enable'/'disable'
 */
function setAccount( user_id, enable )
{
  var uf_check = confirm("Do you want to "+enable+" this membership?");

  if (uf_check == true) {
    makeRequest('?page=backend&type=user&subtype=usrtbl&show=detail&action=account'+enable+'&d_val='+encodeURIComponent(user_id));
  }
}