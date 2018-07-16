$(window).load(function() {
    'use strict';
    $('#text')[0].ondragover = function(){
        return false;
    };
    $('#text')[0].ondragleave = function(){
        return false;
    };
    $('#text')[0].ondrop = function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var file = e.dataTransfer.files[0];
        var data = new FormData();
        data.append('file', file);
        if (file.type === 'application/x-zip-compressed')
            $.ajax({
                url: '',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(d, t){
                    alert(d);
                }
            });
    };
});