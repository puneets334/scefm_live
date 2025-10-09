/**
 * Created by ubuntu on 20/4/17.
 */
var alertId=0;
function clearAllNotify(container){
    if(container){
        container.find('.alert-notify').remove();
        container.find('.text-notify').remove();
    }
    else{
        $('.alert-notify').remove();
        $('.text-notify').remove();
    }
}

function alertNotify(head,desc,status,location,closeable,time){
    alertId=alertId+1;
    var id='alertN-'+alertId;
    var alert=makeAlertNotify(head,desc,status,closeable,id);
    showNotify(location,alert);
    if(time>0){
        alarm('closeNotify',id,time);
    }
    return id;
}

function makeAlertNotify(head,desc,status,closeable,id){
    var alertMsg="";
    alertMsg='<div id="'+id+'" class="alert alert-notify';
    switch(status){
        case 'e':
            alertMsg+=' alert-danger';
            break;
        case 's':
            alertMsg+=' alert-success';
            break;
        case 'w':
            alertMsg+=' alert-warning';
            break;
        case 'i':
            alertMsg+=' alert-info';
            break;
        case 'n':
            alertMsg+=' ';
            break;
        default:
            break;
    }
    alertMsg+='"><strong>'+head+'</strong>&nbsp;'+desc;
    if(closeable==true){
        alertMsg+="<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
    }
    alertMsg+="<br/></div>";
    return alertMsg;
}

function showNotify(location,alert){
    $(location).prepend(alert);
    var position=$(location).offset();
    $("html, body").animate({ scrollTop: position.top }, "slow");
}

function alarm(func,params,time){
    var p = params.split(",");
    func+="(";
    for(var i in p) {
        if(i==0){
            func+="'"+p[i]+"'";
        }
        else{
            func+=","+"'"+p[i]+"'";
        }
    }
    func+=")";
    window.setTimeout(func,time);
}
