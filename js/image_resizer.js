//<![CDATA[
function _image_resizer()
{
  var new_w, new_h;
  var threshold_width;

  for (var i = 0; i < document.images.length; i++)
  {
    threshold_width = document.images[i].parentNode.offsetWidth - 1;
    if(document.images[i].offsetWidth > threshold_width)
    {
      new_w = threshold_width;
      new_h = Math.round ((document.images[i].offsetHeight / document.images[i].offsetWidth) * new_w);
    
      document.images[i].style.width = new_w + 'px';
      document.images[i].style.height = new_h + 'px';
    }
  }
}
function _addEvent(obj, evType, fn)
{
  if (obj.addEventListener)
  {
    obj.addEventListener(evType, fn, false);
    return true;
  }
  else if (obj.attachEvent)
  {
    var r = obj.attachEvent("on" + evType, fn);
    return r;
  }
  else
  {
    return false;
  }
}
_addEvent(window, 'load', _image_resizer);
//]]>