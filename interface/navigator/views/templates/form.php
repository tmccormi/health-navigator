<?php
 // Copyright (C) 2013 Ken Chapple <ken@mi-squred.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.
?>
<html>
<head>
    <link rel="stylesheet" href="<?= $GLOBALS['css_header'] ?>" type="text/css">
    <link media="all" type="text/css" href="http://code.jquery.com/ui/1.8.24/themes/base/jquery-ui.css" rel="stylesheet">
    <link media="all" type="text/css" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.8.24/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $('#save').click( function( e ) { 
                e.preventDefault();
                e.stopPropagation();
            	$.post('<?php echo $this->form->getAction(); ?>', 
            		    $('#<?php echo $this->form->getName(); ?>').serialize(),
            		    function() {
                    		alert('note saved');
                            window.close();
            	        });
            });    

            $("#cancel").click( function() { 
                window.close(); 
            });
        });
   </script>
    <style type="text/css">
        .element .label {
            font-style: bold;
            float: left;   
        }
        
        .element .label {
            float: left;   
        }
    </style>
</head>

<body class='body_top'>
<?php
echo $this->form;
?>

</body>

</html>