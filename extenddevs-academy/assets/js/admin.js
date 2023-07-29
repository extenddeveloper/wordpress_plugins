;(function($){

    $(document).ready(function(){

        $('table.wp-list-table.instructors').on('click', 'a.submitdelete', function(e){
            e.preventDefault();

            let self = $(this);
                id   = self.attr('data-id');

            if(!confirm(wd_admin_util_ajax.confirm)) {
                return ;
            }
            

            // wp.ajax.send('wd-academy-delete-instructor',{
                
            //     data: {
            //         id: id,
            //         _wpnonce: wd_admin_util_ajax.nonce
            //     }
                
            // })
            wp.ajax.post('wd-academy-delete-instructor', {
                id: id,
                _wpnonce: wd_admin_util_ajax.nonce
            })
            .done(function(response){
                console.log(response);
                // remove this row from the table with the data-id attribute and hide it
                self.closest('tr').css('background-color', 'red').hide(400, function(){
                    $(this).remove();
                });

                setTimeout(function(){
                    alert(response.message);
                }, 1500);

            })
            .fail(function(){
                alert(wd_admin_util_ajax.error);
            })



        })

    })

})(jQuery);