<!doctype html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <script src="browser-deeplink.js" type="text/javascript"></script>
    <script type="text/javascript">
    deeplink.setup({
        iOS: {
            appId: "284882215",
            appName: "facebook",
        },
        android: {
            appId: "com.facebook.katana"
        }
    });
    function clickHandler(uri) {
        deeplink.open(uri);
        return false;
    }
    
    
        
        window.onerror = function (msg, url, lineNo, columnNo, error) {
          //alert(msg+"--"+url+"--"+lineNo+"--"+columnNo+"--"+error);
          $("#errorDiv").append("<br><br><br><br><br><br>"+msg+"--"+url+"--"+lineNo+"--"+columnNo+"--"+error);

          return false;
        }
    </script>
    <style>
    *, *:before, *:after {
        box-sizing: border-box;
    }
    a {
        font-family: monospace;
        display: block;
        width: 100%;
        margin: 10px 0;
        padding: 25px;
        background: #060;
        color:#fff;
        text-align: center;
        font-size: 20px;
    }
    </style>
</head>
<body>
    <a href="#" data-uri="fb://profile" onclick="clickHandler(this.dataset.uri)">open fb://profile</a>
        <div id='errorDiv'></div>
</body>
</html>