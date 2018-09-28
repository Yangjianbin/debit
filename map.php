<!DOCTYPE html>
<html>
<head>
    <title>loadmapasyncHTML</title>
    <style type='text/css'>body{margin:0;padding:0;overflow:hidden;font-family:'Segoe UI',Helvetica,Arial,Sans-Serif}</style>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<div id='printoutPanel'></div>

</body>
<script>
    $.ajax(
        {
            type: "POST",
       //     url: "http://api.parking.online10min.com/api/bluetoothPile/v1/add",
        url:'http://140.143.131.31:8081/api/bluetoothPile/v1/add', 
	   data: JSON.stringify({
                name: 'lisi'
            }),
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function(msg)
            {
                console.log(msg)
            },
            error: function(errmsg)
            {
                alert("提交失败！");
            }
        });
</script>
</html>
