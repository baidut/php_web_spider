function refreshPage() {
    jQuery.mobile.changePage(window.location.href, {
        allowSamePageTransition: true,
        transition: "none",
        reloadPage: true
    });
}
function ui_submit(obj){
    var form_data = $(obj).serialize();
    $.ajax({
        type: "POST",
        url: obj.action,
        data: form_data,
        success: function(data,status){alert("Data: " + data + "\nStatus: " + status);$(obj).after(data);$(obj).remove();$('ul').listview('refresh');},
        /*refreshPage();*/
        /*$("p[data-role=content] ul").listview();*/
        error: function(data,status){alert("Data: " + data + "\nStatus: " + status);},
    });
    return false;
}