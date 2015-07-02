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

var itemTable = {
    'sort': 'id',
    'order': 'asc',
    'page': 1
}

function navigate(page) {
    itemTable.page = page;
    reload();
}

function sort(node, name) {
    var order = node.getAttribute('order') || 'asc';
    itemTable.order = order;
    itemTable.sort = name;
    node.setAttribute('order', order == 'asc' ? 'desc' : 'asc');
    reload();
}

function reload() {
    sendAjax('tableBody', 'list.php?sort='+itemTable.sort+'&order=' + itemTable.order + '&page=' + itemTable.page);
}
