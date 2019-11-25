$(document).ready(function(){
    $(".zakupStatus").change(function () {
        let rel = $(this).attr("rel");
        let zakupStatus = $(this).val();
    });
};