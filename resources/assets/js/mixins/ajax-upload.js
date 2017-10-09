//Vue.js doesn't have a ajax out of the box structure so We will use plain old jQuery for that
var _ = require('lodash')

module.exports = {
	methods: {
             ajaxUpload:  function(e) {
                    e.preventDefault();
                    console.log('ajax triggered');
                    var form = $(this);
                    var method = form.find('input[name="_method"]').val() || 'POST';
                    //console.log("serialize:"+form.serialize());
                    $.ajax({
                        type: method,
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function(data) {
                            //console.log(data);
                            if(data.html){
                                  $('.results').html(data.html);
                               }
                            if(data.success==true && data.title ){
                               swal(data.title, data.message, "success");

                               if($(".modal") ){
                                   $('.modal').modal('hide');
                                   form.trigger('reset');
                               }
                               if(data.redirect==true){
                                       setTimeout(function(){
                                           location.reload();
                                        }, 1000);
                                }
                                else if(data.url){
                                    setTimeout(function(){
                                          var url = data.url;
                                            $(location).prop('href',url);
                                        }, 1000);
                                }
                            }
                            else if(data.success==false && data.message ){
                               swal(data.title, data.message, "error");
                             }
                       },
                         error:function(data){
                             if(data.success==false && data.message ){
                               swal(data.title, data.message, "error");
                             }

                         },
                         statusCode: {
                            500: function() {
                             swal('Error', 'An error has occured. Contact the administrators for assistance', "error");
                            }
                        }
                    });
                },

            }//end methods
}//end modules.export
