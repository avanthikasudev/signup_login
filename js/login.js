$(function(){
  if(localStorage.getItem('authToken')){ location.href='profile.html'; return; }
  $('#loginForm').on('submit', function(e){
    e.preventDefault();
    const payload = { email: $('#email').val().trim(), password: $('#password').val() };
    $.ajax({
      url: 'php/login.php', method: 'POST', data: JSON.stringify(payload), contentType: 'application/json', dataType: 'json'
    }).done(function(res){
      const $a = $('#loginAlert').removeClass('d-none alert-danger').addClass('alert');
      if(res.success){
        localStorage.setItem('authToken', res.token);
        localStorage.setItem('userName', res.user.name);
        localStorage.setItem('userEmail', res.user.email);
        localStorage.setItem('userId', String(res.user.id));
        location.href='profile.html';
      } else {
        $a.addClass('alert-danger').text(res.message||'Invalid credentials');
      }
    }).fail(function(){ $('#loginAlert').removeClass('d-none').addClass('alert alert-danger').text('Network error'); });
  });
});


