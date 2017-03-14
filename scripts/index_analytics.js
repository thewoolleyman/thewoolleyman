$(document).ready(function() {
  $('pre a').click(function() {
    if (this.href.match(new RegExp(".*(.)$"))[1] != '/') {
      this.target = "_blank";
    }
    path = this.href.match(new RegExp(".*(" + this.hostname + ")(.*)"))[2];
    urchinTracker(path);
  });
});

