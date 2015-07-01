function getXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

function sendAjax(id, url) {
  var xmlhttp = getXmlHttp()
  xmlhttp.open('GET', url, true);
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4) {
       if(xmlhttp.status == 200) {
         document.getElementById(id).innerHTML = xmlhttp.responseText;
      }
    }
  };
  xmlhttp.send(null);
}

function sort(node, name) {
  var sort = node.getAttribute('sort') || 'asc';
  sendAjax('tableBody', '/list.php?sort='+name+'&order=' + sort); node.setAttribute('sort', sort == 'asc' ? 'desc' : 'asc');
}