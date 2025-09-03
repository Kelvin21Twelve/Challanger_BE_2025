/**
 * ajax call submit
 * @param {*} obj
 */
function ajaxCommonSumitForm(obj) {
    $this = $($(obj));
    $from = $this.closest("form");
    var callback = $this.attr("callback");
   // if (!$from.valid()) return false;
    url = $from.attr('action');
    data = $from.serialize();
    console.log(data);
    $(".server-error").html("");
    ajaxCallPOST(url, data, callback, $this,$from);
}


function ajaxCommonSubmitForm(obj) {
    $this = $($(obj));
    $from = $this.closest("form");
    var callback = $this.attr("callback");
    $(".server-error").html("");
    ajaxgalleryCallPOST(callback, $this,$from);
}



function ajaxgalleryCallPOST(callback, $this,$from) {
    $from = $this.closest("form");
    // alert(JSON.stringify(new FormData($from.get(0))));
    var url=$from.attr('action');
    $.ajax({
        url: url,
        method: 'POST',
        dataType: "JSON",
        data: new FormData($from.get(0)),
        processData: false,
        contentType: false,
        async: false,
        beforeSend: function() {},
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.readyState == 4) {
                swal('HTTP error!!', "error");
            } else if (XMLHttpRequest.readyState == 0) {
                swal('Your Network connection is lost!!', "error");
            } else {
                swal('something weird is happening!!', "error");
            }
            setTimeout(function() { $this.attr("disabled", false); }, 3000);
        },
        success: function(data) {
            $(".error").text("");
            if (data.success == false) {
                if (data.msg_type == 'error') {
                    swal("Oops...", "Something went wrong!", "error");
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
                setTimeout(function() { $this.attr("disabled", false); }, 3000);
            } else {
                if (data.msg_type == 'success') {
                    swal("Good job!", "Information saved successfully!", "success");
                    var loc = window.location;
                    window.location = loc.protocol+"//"+loc.hostname+":"+loc.port+"/add-image";
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                }
            }
        },
        complete: function(data) {}
    });
}

function deleteimage(image_id){
    var url=$('.s_delete').attr("data-url");
    $.ajax({
        url: url,
        method: 'GET',
        data: {
            id:image_id
        },
        contentType: "application/json",
        beforeSend: function() {},
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.readyState == 4) {
                swal('HTTP error!!', "error");
            } else if (XMLHttpRequest.readyState == 0) {
                swal('Your Network connection is lost!!', "error");
            } else {
                swal('something weird is happening!!', "error");
            }
            setTimeout(function() { $this.attr("disabled", false); }, 3000);
        },
        success: function(data) {
            $(".error").text("");
            if (data.success == false) {
                if (data.msg_type == 'error') {
                    swal("Oops...", "Something went wrong!", "error");
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
            } else {
                if (data.msg_type == 'success') {
                    swal("Good job!", "Gallery Image Deleted Successfully!", "success");
                    var loc = window.location;
                    window.location = loc.protocol+"//"+loc.hostname+":"+loc.port+"/add-image";
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
            }
        },
        complete: function(data) {}
    });
}


function setlanguage(){
    var url=$('.setlang').attr("data-route");
    var lang=$('.setlang').attr("data-lang");
    $.ajax({
        url: url,
        method: 'GET',
        data: {
            lang:lang
        },
        contentType: "application/json",
        beforeSend: function() {},
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.readyState == 4) {
                swal('HTTP error!!', "error");
            } else if (XMLHttpRequest.readyState == 0) {
                swal('Your Network connection is lost!!', "error");
            } else {
                swal('something weird is happening!!', "error");
            }
            setTimeout(function() { $this.attr("disabled", false); }, 3000);
        },
        success: function(data) {
            console.log(data,'dataa');
            $(".error").text("");
            if (data.success == false) {
                if (data.msg_type == 'error') {
                    swal("Oops...", "Something went wrong!", "error");
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
            } else {
                if (data.msg_type == 'success') {
                    swal("Good job!", "Language change Successfully!", "success");
                    var loc = window.location;
                    window.location = loc.protocol+"//"+loc.hostname+":"+loc.port+"/add-image";
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
            }
        },
        complete: function(data) {}
    });
}
/**
 * form post call ajax
 * @param {*} url
 * @param {*} data
 * @param {*} callback
 * @param {*} $this
 */
function ajaxCallPOST(url, data, callback, $this,$from) {
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        async: false,
        beforeSend: function() {},
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.readyState == 4) {
                swal('HTTP error!!', "error");
            } else if (XMLHttpRequest.readyState == 0) {
                swal('Your Network connection is lost!!', "error");
            } else {
                swal('something weird is happening!!', "error");
            }
            setTimeout(function() { $this.attr("disabled", false); }, 3000);
        },
        success: function(data) {
            $(".error").text("");
            if (data.success == false) {
                if (data.msg_type == 'error') {
                    swal("Oops...", "Something went wrong!", "error");
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
                setTimeout(function() { $this.attr("disabled", false); }, 3000);
            } else {
                if (data.msg_type == 'success') {
                    swal("Good job!", "Information saved successfully!", "success");
                    $("#contactus").trigger("reset");
                    //window[callback](data, $this);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                }
            }
        },
        complete: function(data) {}
    });
}

