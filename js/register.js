$(function(){
  $('#registerForm').on('submit', function(e){
    e.preventDefault();
    const payload = {
      name: $('#name').val().trim(),
      email: $('#email').val().trim(),
      password: $('#password').val()
    };
    $.ajax({
      url: 'php/register.php',
      method: 'POST',
      data: JSON.stringify(payload),
      contentType: 'application/json',
      dataType: 'json'
    }).done(function(res){
      const $a = $('#registerAlert').removeClass('d-none alert-danger').addClass('alert');
      if(res.success){
        $a.addClass('alert-success').text('Registration successful. Redirecting...');
        setTimeout(()=>location.href='login.html',800);
      } else {
        $a.addClass('alert-danger').text(res.message||'Registration failed');
      }
    }).fail(function(){
      $('#registerAlert').removeClass('d-none').addClass('alert alert-danger').text('Network error');
    });
  });
});


