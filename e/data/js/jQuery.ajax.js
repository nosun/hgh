
    function makeRequest(url) {
        $.getJSON(url, {}, function(messagereturn){ 
            alert(messagereturn); 
        }); 
    }