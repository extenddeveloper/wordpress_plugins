;(function ($) {
    
    $(document).ready(function () {

        $('#extenddevs-enquiry-form form').on('submit', function (e) {

            e.preventDefault();

            var data = $(this).serialize();

            // $.ajax({
            //     'type': 'POST',
            //     'dataType': 'json',
            //     'url': enquiry.ajaxurl,
            //     'data': data,
            //     'success': function (response) {
            //         console.log(response.message);
            //     },
            //     'error': function (response) {
            //         console.log(response.message);
            //     },
            //     'complete': function (response) {
            //         console.log(response.message);
            //     }
            // });

            $.post(enquiry.ajaxurl, data, function (response) {
                console.log(response);
                if(response.success) {
                    alert(response.success);
                } else {
                    alert(response.data.message);
                }
            })
            .fail(function () {
                alert(enquiry.error);
            })

        })

    });

})(jQuery);