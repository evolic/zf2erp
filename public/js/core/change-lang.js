function changeLang(lang) {
  var newUrl = location.href.replace(/[a-z]{2}-[A-Z]{2}/, lang);
  if (newUrl != location.href) {
    return newUrl;
  }

  return location.href;

//  var n = location.href.indexOf('/',7);
//  if (location.href.substr(n) == '/') {
//    return location.href.substr(0,n) + '/' + lang;
//  }
//
//  return location.href.substr(0,n) + '/' + lang + location.href.substr(n);
}
