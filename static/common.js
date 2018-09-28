function getFormJson(frm) {
    var o = {};
    var a = $(frm).serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
}
function number(data){
    if(data!=null){
        return (data + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g,'$&,');
    }else{
        return ;
    }
}