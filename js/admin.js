(function($) {
  "use strict";
  $(function() {
    // Place your administration-specific JavaScript here
  });

  function getWistiaAccount() {
    var wistia = {
      user: 'api',
      password: '',
      accountUrl: 'https://api.wistia.com/v1/account.json'
    };

    $.ajax({
      url: wistia.accountUrl + '?callback=?',
      username: wistia.user,
      password: wistia.password,
      dataType: 'jsonp',
      success: function(data, status) {
        if (console && console.log) {
          console.log(status);
          //console.log(JSON.stringify(data));
          //console.log(data.id);
        }
      }
    });
  }
}(jQuery));
