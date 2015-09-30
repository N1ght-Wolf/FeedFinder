$(document).ready(function() {
  // $('#contact').on('hidden.bs.modal', function () {
  //     $(this).find("input,textarea,select").val('').end();
  //
  // });
  var form = $('#contact-form');
  $(form).bootstrapValidator({
  //        live: 'disabled',
          message: 'This value is not valid',
          feedbackIcons: {
              valid: 'glyphicon glyphicon-ok',
              invalid: 'glyphicon glyphicon-remove',
              validating: 'glyphicon glyphicon-refresh'
          },
          fields: {
              Name: {
                  validators: {
                      notEmpty: {
                          message: 'The Name is required and cannot be empty'
                      }
                  }
              },
              email: {
                  validators: {
                      notEmpty: {
                          message: 'The email address is required'
                      },
                      emailAddress: {
                          message: 'The email address is not valid'
                      }
                  }
              },
              Message: {
                  validators: {
                      notEmpty: {
                          message: 'The Message is required and cannot be empty'
                      }
                  }
              }
          }

      });

});
  // $(form).submit(function(event){
  //   event.preventDefault();
  //   console.log($(this).serialize());
  //   $.ajax({
  //     url:$.ajaxSettings.url + 'send_email',
  //     data: $(this).serialize(),
  //     type:'POST'
  //   });
  // });
