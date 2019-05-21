document.documentElement.className += 'ontouchstart' in document.documentElement ? ' wt_mobile ' : ' wt_desktop ';

Modernizr.load([
  {
    test: Modernizr.mq('only all'),
    nope: theme_uri + '/js/vendor/respond.min.js'
  }
]);