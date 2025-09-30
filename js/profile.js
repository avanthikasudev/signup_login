$(function(){
  const token = localStorage.getItem('authToken');
  if(!token){ location.href='login.html'; return; }
  $('#name').text(localStorage.getItem('userName')||'');
  $('#email').text(localStorage.getItem('userEmail')||'');

  function alertMsg(type, msg){ $('#profileAlert').removeClass('d-none alert-success alert-danger').addClass('alert '+type).text(msg); }

  $.ajax({ url:'php/profile.php', method:'GET', headers:{ 'Authorization':'Bearer '+token }, dataType:'json' }).done(function(res){
    if(res.success && res.profile){ const p=res.profile; $('#age').val(p.age||''); $('#dob').val(p.dob||''); $('#contact').val(p.contact||''); $('#address').val(p.address||''); }
  });

  $('#profileForm').on('submit', function(e){ e.preventDefault();
    const payload={ age:Number($('#age').val()||0), dob:$('#dob').val(), contact:$('#contact').val(), address:$('#address').val() };
    $.ajax({ url:'php/profile.php', method:'POST', data:JSON.stringify(payload), contentType:'application/json', headers:{ 'Authorization':'Bearer '+token }, dataType:'json' })
    .done(function(res){ if(res.success){ alertMsg('alert-success','Profile saved'); } else { alertMsg('alert-danger',res.message||'Failed to save'); } })
    .fail(function(){ alertMsg('alert-danger','Network error'); });
  });

  $('#logoutBtn').on('click', function(){ localStorage.clear(); location.href='login.html'; });
});


